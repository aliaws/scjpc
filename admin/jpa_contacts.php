<?php

require_once SCJPC_PLUGIN_PATH . "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function scjpc_enqueue_export_members_script(): void {
  wp_enqueue_script("jquery");
  wp_enqueue_script("scjpc-export-members", SCJPC_ASSETS_URL . "js/export-jpa-members.js", ["jquery"], "1.27", true);
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
  $export_file_path = scjpc_get_jpa_contacts($_REQUEST);
  return new WP_REST_Response(["export_file" => $export_file_path], 200);

}

add_action('wp_ajax_scjpc_export_jpa_members', 'ajax_scjpc_export_jpa_members');
add_action('wp_ajax_nopriv_scjpc_export_jpa_members', 'ajax_scjpc_export_jpa_members');


function ajax_scjpc_export_jpa_members() {
  [$fields, $jpa_contacts] = scjpc_get_jpa_contacts($_REQUEST);
  [$response, $field_labels] = scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts, 'jpa', true);
  $export_file_path = scjpc_process_contacts_csv_writing($response, $field_labels, 'jpa', true);
  wp_send_json_success(['export_file_path' => $export_file_path], 200);
  wp_die();
}

function scjpc_get_jpa_contacts($query = []): array {
  $fields_group = acf_get_field_group("group_662d0256002a6"); // Server
//  $fields_group = acf_get_field_group("group_666716c1891dd"); // Local
  $fields = acf_get_fields($fields_group);
  if (!empty($query["jpa_contact_id"])) {
    $jpa_contacts = [get_post($query["jpa_contact_id"])];
  } else {
    $jpa_contacts = get_posts([
      "post_type" => "member", // Server
//      "post_type" => "jpa-contact", // Local
      "posts_per_page" => -1,
      "order" => "ASC"
    ]);
  }

  return [$fields, $jpa_contacts];
}

function scjpc_fetch_jpa_contacts_fields(array $fields, array $jpa_contacts, string $group = '', bool $export = false): array {
  $response = [];
  $field_labels = [];
  foreach ($jpa_contacts as $post) {
    $response[$post->ID] = [];
    if ($group == 'pole') {
      [$response, $field_labels] = scjpc_add_pole_additional_fields($response, $field_labels, $post);
    } elseif (in_array($group, ['emergency', 'buddy-pole', 'graffiti-removal', 'field-assistance'])) {
      [$response, $field_labels] = scjpc_add_emergency_additional_fields($response, $field_labels, $post);
    }
    $unwanted_fields = scjpc_get_jpa_contacts_unwanted_fields();
    foreach ($fields as $field) {
      $field_labels[$field['name']] = $field['label'];

      if (!in_array($field["name"], $unwanted_fields)) {
        if ($field["type"] == "wysiwyg") {
//          $response[$post->ID][$field["name"]] = trim(strip_tags(get_field($field["name"], $post->ID)));
//          $response[$post->ID][$field["name"]] = trim(get_field($field["name"], $post->ID));
          if ($export) {
            $response[$post->ID][$field["name"]] = trim(strip_tags(get_field($field["name"], $post->ID)));
          } else {
            $response[$post->ID][$field["name"]] = trim(strip_tags(get_field($field["name"], $post->ID), '<br>'));
          }
        } elseif ($field["type"] == "date_picker") {
          $date = get_field($field["name"], $post->ID);
//          if ($date != '') {
//            $date = DateTime::createFromFormat("d/m/Y", $date)->format('m/d/Y');
//          }
          $response[$post->ID][$field["name"]] = $date;
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


function scjpc_process_contacts_csv_writing(array $data, array $field_labels, string $type, bool $transpose = false): string {
  $spreadsheet = new Spreadsheet();
  $sheet = $spreadsheet->getActiveSheet();
  $excel_file_name = "$type-contacts_" . time() . "_" . get_current_user_id() . ".xlsx";
  $excel_file_path = WP_CONTENT_DIR . "/uploads/scjpc-exports/" . $excel_file_name;
  if ($transpose) {
    $data = scjpc_transpose_contacts_data($data, $field_labels);
  }

  [$row, $column, $max_lengths] = scjpc_write_data_to_sheet($data, $sheet, $type);

  $sheet->getStyle("A1:$column$row")->getFont()->setSize(9);
  $sheet->getStyle("A1:{$column}1")->applyFromArray(scjpc_get_header_style());
  $sheet->getStyle("A1:A$row")->applyFromArray(scjpc_get_first_column_styles());
  $sheet->getStyle("A1:A1")->applyFromArray(scjpc_get_first_row_alignment());
  $sheet->getStyle("B2:$column$row")->applyFromArray(scjpc_get_sheet_styles());

  if (in_array($type, ['pole', 'buddy-pole', 'graffiti-removal'])) {
    $col = 'A';
    foreach ($data[0] as $key => $info) {
      if (in_array($key, ['phone_number', 'pi_last_updated', 'zip_code'])) {
        $sheet->getStyle("{$col}2:$col$row")->applyFromArray(scjpc_get_date_cell_styles());
      }
      $col++;
    }
    unset($col);
  } else {
    foreach ($max_lengths as $key => $info) {
      if (str_ends_with($info['name'], 'last_updated_at')) {
        $sheet->getStyle("B$key:$column$key")->applyFromArray(scjpc_get_date_cell_styles());
      }
    }
  }
  scjpc_set_cells_height_width($sheet, $max_lengths, $row, $type, $data);

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


function scjpc_write_data_to_sheet(array $transposedContacts, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $type): array {
  $max_lengths = [];
  $column = 'A';
  $row = 1;
  foreach ($transposedContacts as $name => $contact) {
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
    foreach ($data[0] as $key => $info) {
      if (in_array($key, ['city', 'state', 'zip_code', 'country', 'pi_last_updated'])) {
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


function scjpc_get_excel_row_height(string $value, string $type): float|int {
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

function scjpc_get_jpa_contacts_unwanted_fields(): array {
  return [
    'cable_tags'
  ];
}