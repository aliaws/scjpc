<?php

require_once SCJPC_PLUGIN_PATH . "vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

function scjpc_enqueue_export_members_script(): void {
    wp_enqueue_script("jquery");
    wp_enqueue_script("scjpc-export-members", SCJPC_ASSETS_URL . "js/export-jpa-members.js", ["jquery"], "1.07", true);
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
    $export_file_path = scjpc_get_jpa_contacts($_REQUEST);
    wp_send_json_success(['export_file_path' => $export_file_path], 200);
    wp_die();
}

function scjpc_get_jpa_contacts($query = []): string {

//  $fields_group = acf_get_field_group("group_662d0256002a6"); // Server
////  $fields_group = acf_get_field_group("group_666716c1891dd"); // Local
//  $fields = acf_get_fields($fields_group);
//  if (!empty($query["jpa_contact_id"])) {
//    $jpa_contacts = [get_post($query["jpa_contact_id"])];
//  } else {
//    $jpa_contacts = get_posts([
//      "post_type" => "member", // Server
////      "post_type" => "jpa-contact", // Local
//      "posts_per_page" => -1,
//      "order" => "ASC"
//    ]);
//  }
    $fields = [];
    $jpa_contacts = [];
    return scjpc_fetch_jpa_contacts_fields($fields, $jpa_contacts);
}

function scjpc_fetch_jpa_contacts_fields(array $fields, array $jpa_contacts): string {
//  $response = [];
//  $field_labels = [];
//  foreach ($jpa_contacts as $post) {
//    $response[$post->ID] = [];
//    foreach ($fields as $field) {
//      $field_labels[$field['name']] = $field['label'];
//      if ($field["name"] != "cable_tags") {
//        if ($field["type"] == "wysiwyg") {
//          $response[$post->ID][$field["name"]] = strip_tags(get_field($field["name"], $post->ID));
//        } else {
//          $response[$post->ID][$field["name"]] = get_field($field["name"], $post->ID);
//        }
//      }
//    }
//  }

    $response = json_decode(file_get_contents(SCJPC_PLUGIN_PATH.'jpa_contacts.json'),true);
    $field_labels = json_decode(file_get_contents(SCJPC_PLUGIN_PATH.'field_labels.json'), true);

    return scjpc_process_contacts_csv_writing($response, $field_labels);
}


function scjpc_process_contacts_csv_writing(array $data, array $field_labels): string {
    $spreadsheet = new Spreadsheet();
    // Set default font size
    $spreadsheet->getDefaultStyle()->getFont()->setSize(8);
    // Set default font family
    $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

    $sheet = $spreadsheet->getActiveSheet();

    $excel_file_name = "jpa-contacts_" . time() . "_" . get_current_user_id() . ".xlsx";
    $excel_file_path = WP_CONTENT_DIR . "/uploads/scjpc-exports/" . $excel_file_name;



//  $excelFile = fopen($excel_file_path, "w");

    $transposedContacts = [];

    foreach ($data[array_key_first($data)] as $key => $value) {
        $transposedContacts[$key] = [$field_labels[$key]];
    }

    foreach ($data as $contact) {
        foreach ($contact as $field => $value) {
            $transposedContacts[$field][] = $value;
        }
    }



    $column = 'A';
    $max_lengths = [];
    $row = 1;
    foreach ($transposedContacts as $contact) {
        $column = 'A';
        foreach ($contact as $value) {
//      echo "<pre>" . print_r($value, true) . "</pre>==$column==$row==\n";
            $sheet->setCellValue($column . $row, $value);
            $max_lengths[$column] = max($max_lengths[$column] ?? 0, strlen($value));
            $column++;
        }
        $row++;
    }

    foreach ($sheet->getColumnIterator() as $sheet_column) {
        $sheet->getColumnDimension($sheet_column->getColumnIndex())->setWidth(30);
    }
    $firstColumnStyle = [
        'font' => [
            'color' => ['argb' => 'FFFFFF'],
            'bold' => true,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '8064a2'
            ],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '8064a2'],
            ],
        ],
    ];


    for ($i = 1; $i < $row; $i++) {
        foreach (range('A', $column) as $col) {
            if ( $i === 1 ) {
                $sheet->getRowDimension($i)->setRowHeight(-1);
            }
            else {
                $sheet->getRowDimension($i)->setRowHeight(40);
            }
        }
        $cell = "A{$i}:A{$i}";
        $sheet->getStyle($cell)->applyFromArray($firstColumnStyle);
    }


    // Define styles for the first row
    $firstRowStyle = [
        'font' => [
            'color' => ['argb' => 'FFFFFF'],
            'size'  => 12,
            'bold' => true,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => '4472c4'
            ],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '4472c4'],
            ],
        ],
    ];
    $endColumn = $sheet->getHighestColumn();
    $range = 'B1:' . $endColumn . '1';
    // Apply styles to the first row
    $sheet->getStyle($range)->applyFromArray($firstRowStyle);

    // Set color of first row and first column to sky blue
//    $sheet->getStyle('A1:' . $column . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFADD8E6');
//    $sheet->getStyle('A1:A' . --$row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFADD8E6');


    $writer = new Xlsx($spreadsheet);
    $writer->save($excel_file_path);

//  fclose($excelFile);
    return wp_get_upload_dir()["baseurl"] . "/scjpc-exports/" . $excel_file_name;

}
