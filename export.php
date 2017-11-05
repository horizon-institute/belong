<php
echo "here we are!";
if(isset($_POST)) {
    $id = $_POST["export_client_select"];

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=pathways.csv');
    $post_id = 337; //post_id for client profile post
    $cm = get_post_meta($post_id, "client_profile_" . $id)[0];
    $output = fopen('php://output', 'w');
    //output client info 
    fputcsv($output, explode(',', $cm));
    fclose($output);
}


?>