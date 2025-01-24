<?php

/**
 * Plugin Name: CSV Data Backup
 * Plugin URI: https://github.com/aflamentTM/table_data_csv_backup
 * Description: This plugin allows you to backup your table data in CSV format.
 * Version: 1.0
 * Author: aflamentTM
 * Author URI:
 */

//  Plugin Menu in admin
add_action('admin_menu', 'tdcb_create_admin_menu');
// Create a page - A Button Export
//  Export the data in CSV format
// Add the menu
function tdcb_create_admin_menu()
{
    add_menu_page('CSV Data Backup Plugin', 'CSV Data Backup', 'manage_options', 'csv-data-backup', 'tdcb_export_form', 'dashicons-database-export', 8);
}
// Form layout
function tdcb_export_form()
{
    ob_start();
    include_once plugin_dir_path(__FILE__) . 'template/table_data_backup.php';
    $layout = ob_get_contents();
    ob_clean();
    echo $layout;
}
// Export the data in CSV format
add_action('admin_init', 'tdcb_handle_form_export');
function tdcb_handle_form_export()
{
    if (isset($_POST['tdcb_export_button'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'students_data';
        $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        if (empty($results)) {
            echo "No data found";
            return;
        }
        $filename = "students_data" . time() . ".csv";
        header('Content-Type: text/csv', 'charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($results[0]));
        foreach ($results as $row) {
            fputcsv($output, $row);
            // foreach ($results as $result) {
            //     $csv_output .= $result->id . ', ' . $result->name . ', ' . $result->email . ', ' . $result->phone . ', ' . $result->message . "\n";
            // }
            // header("Content-type: text/x-csv");
            // header("Content-Disposition: attachment; filename=table_data.csv");
            // echo $csv_output;
            // exit;
        }
        fclose($output);
        exit;
    }
}
