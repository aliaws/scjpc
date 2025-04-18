<?php

require_once SCJPC_PLUGIN_PATH . "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function scjpc_enqueue_export_members_script(): void {
  wp_enqueue_script("jquery");
  wp_enqueue_script("scjpc-export-members", SCJPC_ASSETS_URL . "js/export-jpa-members.js", ["jquery"], "1.58", true);
  wp_localize_script("scjpc-export-members", "scjpc_ajax", ["ajax_url" => admin_url("admin-ajax.php")]);
}

add_action("elementor/frontend/after_enqueue_styles", "scjpc_enqueue_export_members_script");

add_action("rest_api_init", "scjpc_export_jpa_contacts_data");
function scjpc_export_jpa_contacts_data(): void {
  register_rest_route("scjpc/v1/", "jpa-contacts/", [
    "methods" => "GET",
    "callback" => "scjpc_export_jpa_contacts",
  ]);
}


function scjpc_export_jpa_contacts(WP_REST_Request $request): WP_REST_Response {
  $security_key = $request->get_header("security_key");
  if (!isset($security_key) || $security_key != get_option("scjpc_client_auth_key")) {
    return new WP_REST_Response(["message" => "Please provide the API security key"], 401);
  }
  [$fields, $jpa_contacts] = scjpc_get_jpa_contacts($_REQUEST);
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'jpa', true, false);
//  echo "<pre>" . print_r($response, 1) . "</pre>";
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'jpa');
//  $export_file_path = scjpc_get_jpa_contacts($_REQUEST);
  return new WP_REST_Response(["export_file" => $export_file_path], 200);

}

add_action('wp_ajax_scjpc_export_jpa_members', 'ajax_scjpc_export_jpa_members');
add_action('wp_ajax_nopriv_scjpc_export_jpa_members', 'ajax_scjpc_export_jpa_members');


function ajax_scjpc_export_jpa_members() {
//  [$fields, $jpa_contacts] = scjpc_get_jpa_contacts($_REQUEST);
  [$fields, $jpa_contacts] = scjpc_get_jpa_contacts_all_fields($_REQUEST);
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'jpa', true, false);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'jpa');
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}

function acf_get_db_fields($group_id) {
  $data = [];
  $sub_db_fields = get_posts([
    'post_type' => 'acf-field',
    'post_parent' => $group_id,
    'numberposts' => -1, // Retrieve all fields
  ]);
  foreach ($sub_db_fields as $db_field) {
    $data[$db_field->post_name] = $db_field;
  }
  return $data;
}

function scjpc_get_jpa_contacts_all_fields($query = []): array {
  // Server JPA Contacts (?post=315&action=edit)
  $fields_group = acf_get_field_group("group_662d0256002a6");
  $fields = acf_get_fields($fields_group);


  // Server 24 HR EMERGENCY TELEPHONE NUMBER & EMAIL (?post=2655&action=edit)
  $fields_group = acf_get_field_group("group_66464f0e51512");
  $fields = array_merge($fields, acf_get_fields($fields_group));


  // Server FIELD ASSISTANCE/ JOINT MEET *(post=2595&action=edit)
  $fields_group = acf_get_field_group("group_664639ed6409d");
  $sub_fields = acf_get_fields('group_664639ed6409d');
  // I am doing for just making sure correct datrabase reference
  $sub_db_fields = acf_get_db_fields($fields_group['key']);


  foreach ($sub_fields as $key => $field) {
    // Unset Last Updated
    if ($field['key'] == 'field_664639ed86bac') {
      unset($sub_fields[$key]);
    }
    if ($field['key'] == 'field_66463a5e86bad' && isset($sub_db_fields['field_66463a5e86bad'])) {
      $sub_fields[$key]['label'] = $sub_db_fields['field_66463a5e86bad']->post_title;
    }
  }
  $fields = array_merge($fields, $sub_fields);

  // Multiple Document Upload (post=2138&action=edit)
  $fields_group = acf_get_field_group("group_662e732c50241"); // Server
  $fields = array_merge($fields, acf_get_fields($fields_group));

  // Single Points of Contact (?post=3097&action=edit)
  $fields_group = acf_get_field_group("group_665623aed72ff"); // Server
  $fields = array_merge($fields, acf_get_fields($fields_group));
//   echo "<pre>==Count==" . count($fields) . "====" . print_r($fields, 1) . "</pre>";

  if (!empty($query["jpa_contact_id"])) {
    $jpa_contacts = [get_post($query["jpa_contact_id"])];
  } else {
    $jpa_contacts = scjpc_get_jpa_contacts_posts();
//    $jpa_contacts = get_posts([
//      "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//      "posts_per_page" => -1,
//      "order" => "ASC"
//    ]);
  }
  return [$fields, $jpa_contacts];

}

function scjpc_get_jpa_contacts_posts() {
  return get_posts([
    "post_type" => "member", // Server
//      "post_type" => "jpa-contact", // Local
    "posts_per_page" => -1,
    "order" => "ASC",
    "orderby" => "meta_value",
    "meta_key" => "member_code"
  ]);
}

function scjpc_get_jpa_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_662d0256002a6"); // Server
//  $fields_group = acf_get_field_group("group_666716c1891dd"); // Local
  $fields = acf_get_fields($fields_group);
  if (!empty($query["jpa_contact_id"])) {
    $jpa_contacts = [get_post($query["jpa_contact_id"])];
  } else {
    $jpa_contacts = scjpc_get_jpa_contacts_posts();

//    $jpa_contacts = get_posts([
//      "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//      "posts_per_page" => -1,
//      "order" => "ASC"
//    ]);
  }

  return [$fields, $jpa_contacts];
}

function scjpc_fetch_jpa_contacts_fields(array $fields, array $jpa_contacts, string $group = '', bool $export = false, $remove_unwanted = true): array {
  $response = [];
  $field_labels = [];
  foreach ($jpa_contacts as $post) {
    $response[$post->ID] = [];
    if ($group == 'pole') {
      [$response, $field_labels] = scjpc_add_pole_additional_fields($response, $field_labels, $post);

    } elseif (in_array($group, ['emergency', 'buddy-pole', 'graffiti-removal', 'field-assistance', 'cable-tags'])) {
      [$response, $field_labels] = scjpc_add_emergency_additional_fields($response, $field_labels, $post);
    }
    $unwanted_fields = $remove_unwanted ? scjpc_get_jpa_contacts_unwanted_fields($group) : [];
    foreach ($fields as $field) {
      $field_labels[$field['name']] = $field['label'];

      if (!in_array($field["name"], $unwanted_fields)) {

        if ($field["type"] == "wysiwyg") {
          if ($export) {
            $response[$post->ID][$field["name"]] = html_entity_decode(trim(strip_tags(get_field($field["name"], $post->ID))));
          } else {
            $original_data = get_field($field["name"], $post->ID);
            $response[$post->ID][$field["name"]] = $original_data;
          }
        } elseif ($field["type"] == "date_picker") {
          $date = get_field($field["name"], $post->ID);
          $response[$post->ID][$field["name"]] = $date;
        } elseif ($field["type"] == "gallery") {
          $response[$post->ID][$field["name"]] = [];
          $cable_tags = get_field($field["name"], $post->ID);
          if (is_array($cable_tags) && !empty($cable_tags)) {
            $media_array = [];
            foreach ($cable_tags as $image) {
              $file_icon = scjpc_get_extension_icon($image['subtype']);
              if ($export) {
                $media_array[] = $image['url'];
              } else {
                if ($image['title']) {
                  $media_array[] = "<a href='{$image['url']}' class='scjpc-cable-markings'>$file_icon {$image['title']}</a>";
                } else {
                  $media_array[] = "<a href='{$image['url']}' class='scjpc-cable-markings'>$file_icon {$image['filename']}</a>";
                }
              }
            }
            $media_array_separator = $export ? "\n" : " ";
            $response[$post->ID][$field["name"]][] = implode($media_array_separator, $media_array);
          }
          $response[$post->ID][$field["name"]] = implode("\n", $response[$post->ID][$field["name"]]);
        } else {
          try {
            $response[$post->ID][$field["name"]] = trim(get_field($field["name"], $post->ID));
          } catch (\Exception $exception) {
            $response[$post->ID][$field["name"]] = get_field($field["name"], $post->ID);
          }
        }
      }
    }
  }
  return [$response, $field_labels];
//  return scjpc_process_contacts_csv_writing($response, $field_labels);
}

function scjpc_add_emergency_additional_fields(array $response, array $field_labels, $post): array {
  $response[$post->ID]['member_code'] = get_field('member_code', $post->ID);
  $field_labels['member_code'] = 'Member Code';

  $response[$post->ID]['company'] = get_field('company', $post->ID);
  $field_labels['company'] = 'Company';

  return [$response, $field_labels];
}


function scjpc_add_pole_additional_fields(array $response, array $field_labels, $post): array {
  $response[$post->ID]['member_code'] = get_field('member_code', $post->ID);
  $field_labels['member_code'] = 'Member Code';
  return [$response, $field_labels];
}


function scjpc_get_sheet_last_row_column(int $row, string $column): array {
  if (str_ends_with($column, 'AA')) {
    $column = substr_replace($column, 'Z', -strlen('AA'));
  } else {
    $column = str_split($column);
    $column_length = count($column);
    for ($i = $column_length - 1; $i >= 0; $i--) {
      if ($column[$i] != 'A') {
        $column[$i] = chr(ord($column[$i]) - 1);
        break;
      } else {
        $column[$i] = 'Z';
        if ($i === 0 && $column_length > 1 && $column[1] === 'A') {
          $column = implode('', $column);
        }
      }
    }
    $column = implode('', $column);
  }
  return [--$row, $column];
}


function scjpc_process_contacts_csv_writing(array $contacts, array $field_labels, string $type, bool $transpose = false): string {
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  $file_name = isset($_GET['export_file_name']) ? $_GET['export_file_name'] . "_" : "$type-contacts_";
  $excel_file_name = $file_name . time() . "_" . get_current_user_id() . ".xlsx";
  $excel_file_path = WP_CONTENT_DIR . "/uploads/scjpc-exports/" . $excel_file_name;

  if ($transpose) {
    $contacts = scjpc_transpose_contacts_data($contacts, $field_labels);
  }

  [$row, $column, $max_lengths] = scjpc_write_data_to_sheet($contacts, $sheet, $type, $field_labels);

  $sheet->getStyle("A1:$column$row")->getFont()->setSize(9);
  $sheet->getStyle("A1:{$column}1")->applyFromArray(scjpc_get_header_style());
  $sheet->getStyle("A1:A$row")->applyFromArray(scjpc_get_first_column_styles());
  $sheet->getStyle("A1:A1")->applyFromArray(scjpc_get_first_row_alignment());
  $sheet->getStyle("B2:$column$row")->applyFromArray(scjpc_get_sheet_styles());

//  if (in_array($type, ['pole', 'buddy-pole', 'graffiti-removal'])) {
  $col = 'A';
  foreach ($contacts[array_key_first($contacts)] as $key => $info) {
    if (in_array($key, ['phone_number', 'pi_last_updated', 'zip_code'])) {
      $sheet->getStyle("{$col}2:$col$row")->applyFromArray(scjpc_get_date_cell_styles());
    }
    $col++;
  }
  unset($col);
//  } else {
//    foreach ($max_lengths as $key => $info) {
//      if (str_ends_with($info['name'], 'last_updated_at')) {
//        $sheet->getStyle("B$key:$column$key")->applyFromArray(scjpc_get_date_cell_styles());
//      }
//    }
//  }
  scjpc_set_cells_height_width($sheet, $max_lengths, $row, $type, $contacts);

  $sheet->freezePane('B2');
  $writer = new Xlsx($spreadsheet);
  $writer->save($excel_file_path);
  $spreadsheet->disconnectWorksheets();
  return wp_get_upload_dir()["baseurl"] . "/scjpc-exports/" . $excel_file_name;

}

function scjpc_transpose_contacts_data(array $data, array $field_labels): array {
  $transposedContacts = [];
  foreach ($data[array_key_first($data)] as $key => $value) {
    $transposedContacts[$key] = [$field_labels[$key]];
  }

  foreach ($data as $contact) {
    foreach ($contact as $field => $value) {
      $transposedContacts[$field][] = $value;
    }
  }
  return $transposedContacts;
}


function scjpc_write_data_to_sheet(array $contacts, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $type, $field_labels): array {
  $max_lengths = [];
  $column = 'A';
  $row = 1;
  foreach ($contacts[array_key_first($contacts)] as $key => $value) {
    $sheet->setCellValue($column . $row, $field_labels[$key] ?? trim(ucwords(str_replace("_", " ", $key))));
    $max_lengths[$row] = [
      'length' => 50,
      'name' => $field_labels[$key] ?? trim(ucwords(str_replace("_", " ", $key)))
    ];
    $column++;
  }
  $row++;
  foreach ($contacts as $name => $contact) {
    $column = 'A';
    foreach ($contact as $key => $value) {
      $sheet->setCellValue($column . $row, $value);
      if (empty($max_lengths[$row])) {
        $max_lengths[$row] = ['length' => 0, 'name' => ''];
      }
      $max_lengths[$row] = [
        'length' => max($max_lengths[$row]['length'], scjpc_get_excel_row_height($value, $type)),
        'name' => in_array($type, ['pole', 'buddy-pole', 'graffiti-removal']) ? $key : $name
      ];
      $column++;
    }
    $row++;
  }
  [$row, $column] = scjpc_get_sheet_last_row_column($row, $column);
  return [$row, $column, $max_lengths];
}


function scjpc_set_cells_height_width(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, array $max_lengths, int $row, string $type, array $data): void {
  for ($row_index = 1; $row_index <= $row; $row_index++) {
    if ($row_index == 1 && in_array($type, ['pole', 'buddy-pole', 'graffiti-removal'])) {
      $sheet->getRowDimension($row_index)->setRowHeight($max_lengths[$row_index]['length'] + 12);
    } else {
      $sheet->getRowDimension($row_index)->setRowHeight($max_lengths[$row_index]['length']);
    }
  }
  if (in_array($type, ['pole', 'buddy-pole', 'graffiti-removal'])) {
    $col = 'A';
    foreach ($data[array_key_first($data)] as $key => $info) {
      if (in_array($key, ['city', 'state', 'zip_code', 'country', 'county', 'pi_last_updated', 'cable_tags'])) {
        $sheet->getColumnDimension($col++)->setAutoSize(true);
      } else {
        $sheet->getColumnDimension($col++)->setWidth(21);
      }
    }
  } else {
    foreach ($sheet->getRowIterator() as $row) {
      foreach ($row->getCellIterator() as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setWidth(23);
      }
    }
  }
}


function scjpc_get_date_cell_styles(): array {
  return [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
      'wrapText' => true,
    ]
  ];
}


function scjpc_get_sheet_styles(): array {
  return [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
      'wrapText' => true,
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => ['rgb' => '000000'],
      ],
    ],
  ];
}


function scjpc_get_first_row_alignment(): array {
  return [
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'wrapText' => true,
    ]
  ];
}


function scjpc_get_first_column_styles(): array {
  return [
    'font' => [
      'bold' => true,
      'size' => 9,
      'color' => ['rgb' => 'FFFFFF'],
    ],
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
      'wrapText' => true,
    ],
    'fill' => [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => ['rgb' => '8064A2'],
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => ['rgb' => 'FFFFFF'],
      ],
    ],
  ];
}


function scjpc_get_header_style(): array {
  return [
    'font' => [
      'bold' => true,
      'size' => 9,
      'color' => ['rgb' => 'FFFFFF'],
    ],
    'alignment' => [
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'wrapText' => true,
    ],
    'fill' => [
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => ['rgb' => '0070C0'],
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => ['rgb' => 'FFFFFF'],
      ],
    ],
  ];
}


function scjpc_get_excel_row_height(string $value, string $type) {
  $str_array = explode("\n", $value);
  $height = 0;
  foreach ($str_array as $str) {
    $height += 1;
    if ($type == 'jpa' && strlen($str) > 25) {
      $height += ceil(strlen($str) / 25) - 1;
    } elseif ($type != 'jpa' && strlen($str) > 22) {
      $height += ceil(strlen($str) / 20) - 1;
    }
  }
  return $height <= 1 ? 13 : 10.6 * $height;
}

function scjpc_get_jpa_contacts_unwanted_fields($group = ''): array {
  if ($group === 'pole') {
    return ['cable_tags'];
  }
  return ['cable_tags', 'pole_inspection_contacts2'];
}
