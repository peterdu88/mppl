<?php
/**
 * User: peter
 * Date: 1/12/2015 0012
 * Time: 2:23 PM
 * Method: addFlyer, editFlyer, deleteFlyer,getFlyer, getAllFlyersList, getFlyerDescription, getTotalFlyers,
 *          checkFlyer, getStoreByFlyerId
 *          addFlyerCategory, editFlyerCategory,deleteFlyerCategory,getFlyerCategory,
 *          getFlyerCategoryDescriptions,getTotalFlyerCategory
 *
 *          getFlyerStores
 */

class ModelMosheFlyer extends Model {

    public function addFlyer($data){
        $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer
                        SET status = '" . (int)$data['status'] . "',
                            date_added = now()"
        );
        $flyer_id = $this->db->getLastId();
/*        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET image = '" . $this->db->escape($data['image']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }*/
        if (isset($data['image_thumbnail'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET image_thumbnail = '" . $this->db->escape($data['image_thumbnail']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }
        if (isset($data['flyer_pdf_file'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET flyer_pdf_file = '" . $this->db->escape($data['flyer_pdf_file']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }

        if(isset($data['mask'])){
            $this->db->query("UPDATE ". DB_PREFIX ."moshe_flyer SET mask = '" .htmlentities($data['mask']) ."'
                WHERE flyer_id = ".(int)$flyer_id);
        }

        // update the flyer on home page or on top
        if(isset($data['top_show'])){
            $this->db->query("UPDATE ". DB_PREFIX ."moshe_flyer SET top_show = " .(int)$data['top_show'] ."
                WHERE flyer_id = ".(int)$flyer_id);
        }

        if (isset($data['flyer_start_time'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET flyer_start_time = '" . $this->db->escape($data['flyer_start_time']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }
        if (isset($data['flyer_end_time'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET flyer_end_time = '" . $this->db->escape($data['flyer_end_time']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }
        if (isset($data['flyer_image'])) {
            foreach ($data['flyer_image'] as $flyer_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_image SET flyer_id = '" . (int)$flyer_id . "', image = '" . $this->db->escape(html_entity_decode($flyer_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$flyer_image['sort_order'] . "'");
            }
        }
        foreach ($data['flyer_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_description
                            SET flyer_id = '" . (int)$flyer_id . "',
                                category_id = '" . (int)$value['category_id'] . "',
                                language_id = '" . (int)$language_id . "',
                                title = '" . $this->db->escape($value['title']) . "',
                                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                                description = '" . $this->db->escape($value['description']) . "'"
            );
        }

        if ($data['keyword']) {
            $data['keyword'] = preg_replace('\ ','-',$data['keyword']);
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
                            SET query = 'flyer_id=" . (int)$flyer_id . "',
                                keyword = '" . $this->db->escape($data['keyword']) . "'"
            );
        }
        if (isset($data['flyer_store'])) {
            foreach ($data['flyer_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_to_store
                                SET flyer_id = '" . (int)$flyer_id . "',
                                    store_id = '" . (int)$store_id . "'"
                );
            }
        }
        $this->cache->delete('moshe_flyer');

    }

    public function editFlyer($flyer_id,$data){

        $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                        SET status = '" . (int)$data['status'] .
                        "' WHERE flyer_id = '" . (int)$flyer_id . "'"
        );
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_description
                        WHERE flyer_id = '" . (int)$flyer_id . "'"
        );

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                            SET image = '" . $this->db->escape($data['image'])
                            . "' WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }

        // update the flyer on home page or on top
        if(isset($data['top_show'])){
            $top_show = (int)$data['top_show'] ;
        }else{
            $top_show = 0;
        }

        $this->db->query("UPDATE ". DB_PREFIX ."moshe_flyer SET top_show = " . $top_show ."
                WHERE flyer_id = ".(int)$flyer_id);

        if (isset($data['image_thumbnail'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                            SET image_thumbnail = '" . $this->db->escape($data['image_thumbnail'])
                . "' WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }
        if (isset($data['flyer_pdf_file'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                            SET flyer_pdf_file = '" . $this->db->escape($data['flyer_pdf_file'])
                . "' WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }

        if(isset($data['mask'])){
            $this->db->query("UPDATE ". DB_PREFIX ."moshe_flyer SET mask = '" .htmlentities($data['mask']) ."'
                WHERE flyer_id = ".(int)$flyer_id);
        }

        if (isset($data['flyer_start_time'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET flyer_start_time = '" . $this->db->escape($data['flyer_start_time']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }
        if (isset($data['flyer_end_time'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer
                             SET flyer_end_time = '" . $this->db->escape($data['flyer_end_time']) . "'
                             WHERE flyer_id = '" . (int)$flyer_id . "'"
            );
        }

        foreach ($data['flyer_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_description
                            SET flyer_id = '" . (int)$flyer_id . "',
                                category_id = '" . (int)$value['category_id'] . "',
                                language_id = '" . (int)$language_id . "',
                                title = '" . $this->db->escape($value['title']) . "',
                                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                                description = '" . $this->db->escape($value['description']) . "'"
            );
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_image WHERE flyer_id = '" . (int)$flyer_id . "'");
        if (isset($data['flyer_image'])) {
            foreach ($data['flyer_image'] as $flyer_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_image SET flyer_id = '" . (int)$flyer_id . "', image = '" . $this->db->escape(html_entity_decode($flyer_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$flyer_image['sort_order'] . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias
                         WHERE query = 'flyer_id=" . (int)$flyer_id. "'"
        );
        if (isset($data['keyword']) && $data['keyword'] != '') {
            $keyword = trim($data['keyword']);
            $data['keyword'] = preg_replace('/[[:blank:]]+/','-',strtolower($keyword));
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias
                            SET query = 'flyer_id=" . (int)$flyer_id . "',
                                keyword = '" . $this->db->escape($data['keyword']) . "'"
            );
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_to_store
                            WHERE flyer_id = '" . (int)$flyer_id . "'"
        );

        if (isset($data['flyer_store'])) {
            foreach ($data['flyer_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_to_store
                            SET flyer_id = '" . (int)$flyer_id . "',
                                store_id = '" . (int)$store_id . "'"
                );
            }
        }
        $this->cache->delete('moshe_flyer');
    }

    public function getFlyer($id)
    {
        /*        $query = $this->db->query(
                    "SELECT DISTINCT *,
                      (SELECT keyword
                        FROM " . DB_PREFIX . "url_alias
                        WHERE query = 'flyer_id=" . (int)$id . "') AS keyword
                      FROM " . DB_PREFIX . "moshe_flyer WHERE flyer_id = '" . (int)$id . "'");*/
        $query = $this->db->query(
            "SELECT DISTINCT mf.*,mfd.*,
              (SELECT keyword
                FROM " . DB_PREFIX . "url_alias
                WHERE query = 'flyer_id=" . (int)$id . "') AS keyword
              FROM " . DB_PREFIX . "moshe_flyer mf
                LEFT JOIN ".DB_PREFIX."moshe_flyer_description mfd ON (mfd.flyer_id = mf.flyer_id)
                LEFT JOIN ".DB_PREFIX."moshe_flyer_to_store mf2s ON (mf.flyer_id = mf2s.flyer_id)
              WHERE mf.flyer_id = '" . (int)$id . "' AND mfd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND mf2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ");
        return $query->row;
    }

    public function getAllFlyersList($data = array()){
        $sql = "SELECT mf.*, mfd.*, mfcl.title as category_title
              FROM " . DB_PREFIX . "moshe_flyer mf
                LEFT JOIN " . DB_PREFIX . "moshe_flyer_description mfd
                    ON (mf.flyer_id = mfd.flyer_id)
                    LEFT JOIN " . DB_PREFIX . "moshe_flyer_category_language mfcl
                        ON (mfd.category_id=mfcl.category_id
                        AND mfd.language_id=mfcl.language_id)
              WHERE mfd.language_id = '" . (int)$this->config->get('config_language_id') . "'
              ORDER BY mf.date_added";

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function deleteFlyer($flyer_id){
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer
                        WHERE flyer_id = '" . (int)$flyer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_description
                        WHERE flyer_id = '" . (int)$flyer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias
                        WHERE query = 'flyer_id=" . (int)$flyer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_to_store
                        WHERE flyer_id = '" . (int)$flyer_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_image
        WHERE flyer_id = '" . (int) $flyer_id . "'");

        $this->cache->delete('moshe_flyer');
    }

    public function getStoreByFlyer($id){
        $flyerpage_store_data = array();
        $query = $this->db->query(
            "SELECT *
            FROM " . DB_PREFIX . "moshe_flyer_to_store
            WHERE flyer_id = '" . (int)$id . "'"
        );

        foreach ($query->rows as $result) {
            $flyerpage_store_data[] = $result['store_id'];
        }
        return $flyerpage_store_data;
    }

    public function getFlyerAllCategories($data_filter) {
        $news_category_data = array();
        $sql = "SELECT *
            FROM " . DB_PREFIX . "moshe_flyer_category mf
                LEFT JOIN " . DB_PREFIX . "moshe_flyer_category_language mfcl
                    ON (mf.category_id = mfcl.category_id)";

        if(isset($data_filter['status'])){
            $sql .=" WHERE mf.status = 1";
        }
        $sql .= " ORDER BY mf.date_added ";

        if(isset($data_filter['order']) ) {
            if ($data_filter['order'] == "ASC" || $data_filter['order'] == "DESC") {
                $sql .= $data_filter['order'];
            }
            else{
                $sql .= " ASC";
            }
        }else{
            $sql .= " ASC";
        }

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $news_category_data[$result['language_id']][] =$result;
        }
        return $news_category_data;
    }

    public function getFlyerAllCategoriesbyDefaultLanguage($data = array()) {
        $flyer_category_data = array();

        $sql = "SELECT *
            FROM " . DB_PREFIX . "moshe_flyer_category mf
                LEFT JOIN " . DB_PREFIX . "moshe_flyer_category_language mfcl
                    ON (mf.category_id = mfcl.category_id)
                WHERE mfcl.language_id = '" . (int)$this->config->get('config_language_id') . "'
                ORDER BY mf.date_added";

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $flyer_category_data[] =$result;
        }

        return $flyer_category_data;
    }

    public function getFlyerDescriptions($flyer_id) {
        $flyer_description_data = array();
        $query = $this->db->query(
            "SELECT *
            FROM " . DB_PREFIX . "moshe_flyer_description
            WHERE flyer_id = '" . (int)$flyer_id . "'"
        );
        foreach ($query->rows as $result) {
            $flyer_description_data[$result['language_id']] = array(
                'title'            => $result['title'],
                'category_id'      => $result['category_id'],
                'meta_description' => $result['meta_description'],
                'description'      => $result['description']
            );
        }
        return $flyer_description_data;
    }

    public function getFlyerImages($flyer_id){

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "moshe_flyer_image
        WHERE flyer_id = '" . (int)$flyer_id . "'");

        return $query->rows;
    }

    public function getTotalFlyers() {
        $this->checkFlyers();
        $query = $this->db->query("SELECT COUNT(*) AS total
                                  FROM " . DB_PREFIX . "moshe_flyer");
        return $query->row['total'];
    }

    public function addFlyerCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_category
                            SET status = '" . (int)$data['status'] . "',
                                date_added = now()"
        );

        $category_id = $this->db->getLastId();
        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_category_language
                            SET  language_id = '" . (int)$language_id . "',
                                 category_id = '" . (int)$category_id . "',
                                 title = '" . $this->db->escape($value['title']) . "',
                                 date_added = now() "
            );
        }

        $this->cache->delete('moshe_flyer');
    }

    public function editFlyerCategory($category_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "moshe_flyer_category
                            SET status = '" . (int)$data['status'] . "'
                            WHERE category_id = '" . (int)$category_id . "'"
        );
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_category_language
                            WHERE category_id = '" . (int)$category_id . "'");

        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "moshe_flyer_category_language
                                SET category_id = '" . (int)$category_id . "',
                                language_id = '" . (int)$language_id . "',
                                title = '" . $this->db->escape($value['title']) . "'"
            );
        }

        $this->cache->delete('moshe_flyer');
    }

    public function deleteFlyerCategory($category_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_category
                        WHERE category_id = '" . (int)$category_id . "'"
        );
        $this->db->query("DELETE FROM " . DB_PREFIX . "moshe_flyer_category_language
                        WHERE category_id = '" . (int)$category_id . "'"
        );
        $this->cache->delete('moshe_flyer');
    }

    public function getFlyerCategoryDescriptions($category_id) {
        $category_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "moshe_flyer_category_language
                                        WHERE category_id = '" . (int)$category_id . "'"
        );
        foreach ($query->rows as $result) {

            $category_description_data[$result['language_id']] = $result;

        }

        return $category_description_data;
    }

    public function getTotalFlyerCategory() {
        $this->checkFlyers();
        $sql = "SELECT COUNT(*) AS total
                                FROM " . DB_PREFIX . "moshe_flyer_category";

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getFLyerDefaultCategoryById($id){
        $flyer_category_data = array();
        $query = $this->db->query("SELECT *
                                    FROM " . DB_PREFIX . "moshe_flyer_category mf
                                    WHERE mf.category_id = '" . (int)$id . "'"
        );
        foreach($query->rows as $key => $value){
            $flyer_category_data = $value;
        }

        return $flyer_category_data;
    }

    public function getFlyerCategoryById($category_id) {
        $flyer_category_data = array();
        $query = $this->db->query(
            "SELECT *
            FROM " . DB_PREFIX . "moshe_flyer_category mf
            WHERE mf.category_id = '" . (int)$category_id . "'"
        );

        foreach($query->rows as $key => $value){
            $flyer_category_data = $value;
        }

        return $flyer_category_data;
    }

    public function checkFlyers() {
        $create_flyers_category_table =

              "CREATE TABLE
                IF NOT EXISTS `" . DB_PREFIX . "moshe_flyer_category`
                (`category_id` int(11) NOT NULL auto_increment,
                 `status` int(1) NOT NULL default '0',
                  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                  PRIMARY KEY  (`category_id`))
                  ENGINE=MyISAM
                  DEFAULT CHARSET=utf8
                  COLLATE=utf8_bin;";
        $this->db->query($create_flyers_category_table);

        $create_flyer_category_language_table =
            "CREATE TABLE
                IF NOT EXISTS `" . DB_PREFIX . "moshe_flyer_category_language`
                (`id` int(11) NOT NULL auto_increment,
                `language_id` int(11) NOT NULL default '0',
                `category_id` int(11) NOT NULL default '0',
                `title` varchar(64) collate utf8_bin NOT NULL default '',
                `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                 PRIMARY KEY  (`id`))
                 ENGINE=MyISAM
                 DEFAULT CHARSET=utf8
                 COLLATE=utf8_bin;";
        $this->db->query($create_flyer_category_language_table);

        $create_flyer_table =
            "CREATE TABLE
            IF NOT EXISTS `" . DB_PREFIX . "moshe_flyer` (
            `flyer_id` int(11) NOT NULL AUTO_INCREMENT,
              `status` int(1) NOT NULL DEFAULT '0',
              `top_show` int(1) NOT NULL DEFAULT '0' COMMENT 'Mark Flyer on top show',
              `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
              `image_size` int(1) NOT NULL DEFAULT '0',
              `image_thumbnail` varchar(255) COLLATE utf8_bin DEFAULT NULL,
              `image_thumbnail_size` int(1) NOT NULL DEFAULT '0',
              `flyer_pdf_file` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Flyer PDF file name',
              `flyer_pdf_file_size` int(1) NOT NULL DEFAULT '0',
              `mask` varchar(128) CHARACTER SET utf8 NOT NULL,
              `flyer_start_time` date NOT NULL DEFAULT '0000-00-00' COMMENT 'The flyer will be effected',
              `flyer_end_time` date NOT NULL DEFAULT '0000-00-00' COMMENT 'The flyer end time',
              `remaining` int(11) NOT NULL DEFAULT '0',
              `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              PRIMARY KEY (`flyer_id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
        $this->db->query($create_flyer_table);

        $flyer_image_table_create ="
          CREATE TABLE
          IF NOT EXISTS `" . DB_PREFIX . "moshe_flyer_image` (
          `flyer_image_id` int(11) NOT NULL AUTO_INCREMENT,
          `flyer_id` int(11) NOT NULL,
          `image` varchar(255) DEFAULT NULL,
          `sort_order` int(3) NOT NULL DEFAULT '0',
          PRIMARY KEY (`flyer_image_id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

        $this->db->query($flyer_image_table_create);

        $create_flyer_descriptions_table =
            "CREATE TABLE
                IF NOT EXISTS `" . DB_PREFIX . "moshe_flyer_description`
                (`flyer_id` int(11) NOT NULL default '0',
                `category_id` int(11) NOT NULL default '0',
                `language_id` int(11) NOT NULL default '0',
                `title` varchar(64) collate utf8_bin NOT NULL default '',
                `meta_description` varchar(255) collate utf8_bin NOT NULL,
                `description` text collate utf8_bin NOT NULL,
                PRIMARY KEY  (`flyer_id`,`language_id`))
                ENGINE=MyISAM
                DEFAULT CHARSET=utf8
                COLLATE=utf8_bin;";
        $this->db->query($create_flyer_descriptions_table);



        $create_flyer_to_store_table =
            "CREATE TABLE
                IF NOT EXISTS `" . DB_PREFIX . "moshe_flyer_to_store`
                (`flyer_id` int(11) NOT NULL,
                `store_id` int(11) NOT NULL,
                PRIMARY KEY  (`flyer_id`, `store_id`))
                ENGINE=MyISAM
                DEFAULT CHARSET=utf8
                COLLATE=utf8_bin;";
        $this->db->query($create_flyer_to_store_table);
    }
}
?>