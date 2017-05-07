<?php

require_once dirname( __FILE__ ) . '/include.php';

class EbpCategories {

  public static function getTablesSQL() {
    $categoriesTable = EbpDatabase::getTableName("categories");
    $categorySQL = "CREATE TABLE " . $categoriesTable ." (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            PRIMARY KEY (id)
          );";

    $categoryEventsTable = EbpDatabase::getTableName("categoryEvents");
    $categoryEventsSQL = "CREATE TABLE " . $categoryEventsTable ." (
            id INT NOT NULL AUTO_INCREMENT ,
            event int NOT NULL,
            category int NOT NULL,
            PRIMARY KEY (id)
          );";

    return array($categorySQL, $categoryEventsSQL);
  }

  /**
  * FUNCTIONS
  */

  public static function getEventCategories ($id) {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("categoryEvents")." where event='$id'");

    return $results;
  }

  public static function getEventCategoriesFull ($id) {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("categoryEvents")." as eventCat inner join ".EbpDatabase::getTableName("categories")." as cat on eventCat.category = cat.id where eventCat.event='$id'");

    return $results;
  }

   public static function eventIdentificationClasses ($id) {
    $results = self::getEventCategories($id);

    $classes = ' isAnEvent';
    foreach ($results as $result) {
      $classes  .= ' ebpCat_'.$result->category;
    }

    return $classes;
  }

  public static function getUsedCategories($categories) {
    global $wpdb;

    $categories = preg_replace('/\s+/', '', $categories);

    $sql = "SELECT cat.id, cat.name, COUNT(catEvent.category) AS total FROM ". EbpDatabase::getTableName("categories")." AS cat CROSS JOIN ".EbpDatabase::getTableName("categoryEvents")." AS catEvent where catEvent.category = cat.id ";

    if ($categories != NULL || $categories != "") {
      $sql .= "and cat.id in (".$categories.") ";
    }

    $sql .= "GROUP BY catEvent.category";


    $categoriesList = $wpdb->get_results($sql);

    $list = json_decode(json_encode($categoriesList), true);

    return $list;
  }


  public static function eventBelongsToCategories($id, $categories) {
    if ($categories == NULL || $categories == "") return true;

    $okay = false;

    $categories = preg_replace('/\s+/', '', $categories);
    $catIDs = explode(",", $categories);

    $results = self::getEventCategories($id);
    foreach ($results as $result ) {
      if (in_array($result->category, $catIDs)) {
        $okay = true;
        break;
      }
    }

    return $okay;
  }


  /**
  * ADMIN PAGE FUNCTIONS
  */
  public static function getCategoriesAdminPage() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM " . EbpDatabase::getTableName("categories"));

    $html = '<h2>Categories</h2>';
    $html .= '<div class="categories">';

    foreach($results as $result) {
      $html .= '<a href="#" class= "category editCategory" data-id="'.$result->id.'">'.stripslashes($result->name).' (id: '.$result->id.')</a>';
    }

    $maxID = intval($wpdb->get_var( "select max(id) from " . EbpDatabase::getTableName("categories") ))+1;

    $html .= '<a href="#" class= "category newCategory" data-id="'.$maxID.'">+ Add new category</a>';
    $html .= '</div>';

    $html .= '<div class="EBP--CategoryDetails">';
    $html .= '</div>';

    return $html;
  }

  public static function getCategory($id) {
    global $wpdb;
    $result = $wpdb->get_row("SELECT * FROM " . EbpDatabase::getTableName("categories")." where id='$id'");

    if ($result != NULL) {
      return $result;
    } else {
      return array('error' => 'Error while getting coupon data');
    }
  }

  public static function saveCategory($id, $name) {
    global $wpdb;
    $tableName = EbpDatabase::getTableName("categories");

    $available = $wpdb->get_var("select COUNT(*) from " . $tableName ." where id= '$id'");

    if ($available > 0) {
      $wpdb->update($tableName, array('name'=> $name), array( 'id'=> $id ) );
    } else {
      $wpdb->insert($tableName, array('id'=> $id, 'name'=> $name));
    }

    $maxId = intval($wpdb->get_var( "select max(id) from " . $tableName )) + 1;

    $html = $name . ' (id: ' . $id . ')';
    $return = array('error' => null, 'maxId' => $maxId, 'id' =>$id, 'html' => $html);
    return $return;
  }

  public static function deleteCategory($id) {
    global $wpdb;
    $wpdb->delete(EbpDatabase::getTableName("categories"), array('id'=> $id));
    $wpdb->delete(EbpDatabase::getTableName("categoryEvents"), array('category'=> $id));
    return $id;
  }

  public static function getEventCategoriesAdminPage($event) {
    global $wpdb;
    $categoriesTable = EbpDatabase::getTableName("categories");
    $categoryEventsTable = EbpDatabase::getTableName("categoryEvents");
    $results = $wpdb->get_results("SELECT * FROM " . $categoriesTable);

    $data = '';

    foreach ($results as $result ) {
      $id = $result->id;

      $active = "notselected";
      $isAvilable = $wpdb->get_var( "select COUNT(*) from ". $categoryEventsTable." where event= '$event' and category= '$id'");

      if ($isAvilable > 0) $active = "";

      $data .= '<a href="#" class= "category toggle '.$active.'" data-id="'.$id.'">'.stripslashes($result->name).' (ID: '.$id.')</a>';
    }

    return '<div class= "categories">' . $data . '</div>';;
  }

  public static function updateEventCategories($params) {
    global $wpdb;
    $event = $params['id'];
    $category = '';
    $categoryEventsTable = EbpDatabase::getTableName("categoryEvents");

    foreach ($params as $key=> $value) {
      list($type, $id) = explode('-', $key);

      if ($type == "categoryid") {
        $category = $value;
      } else if ($type == "selected") {
        if ($value == "false") {
          $isAvilable = $wpdb->get_var( "select COUNT(*) from " . $categoryEventsTable ." where event= '$event' and category= '$category'");

          if ($isAvilable == 0) {
            $wpdb->insert($categoryEventsTable, array('event'=> $event, 'category'=> $category));
          }

        } else if ($value == "true") {
          $wpdb->delete( $categoryEventsTable, array( 'event'=> $event, 'category'=> $category ) );
        }
      }
    }

    return true;
  }
}
?>
