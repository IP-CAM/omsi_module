<?php
class SqlConstants {
    // Common
    const GET_LAST_INSERT_ID = "SELECT LAST_INSERT_ID()";

    // SELECTs
    const GET_ALL_PRODUCTS_IDS_WITH_NAMES =
        "SELECT pr.product_id, descr.name
         FROM oc_product pr, oc_product_description descr
         WHERE pr.product_id = descr.product_id;";

    const GET_ALL_CATEGORIES_IDS_WITH_NAMES =
        "SELECT cat.category_id, descr.name
         FROM oc_category cat, oc_category_description descr
         WHERE cat.category_id = descr.category_id;";

    const GET_PRODUCT_BY_MODEL = "SELECT * from oc_product where model = '?'";

    const GET_ATTRIBUTE_GROUP_ID_BY_NAME = "SELECT attribute_group_id from oc_attribute_group_description where name like '%?%'";

    const GET_ATTRIBUTE_ID_BY_UUID = "SELECT attribute_id from oc_ms_samopek_attributes where ms_attribute_uuid = '?'";

    const GET_PRODUCT_ID_BY_UUID = "SELECT product_id from oc_ms_samopek_product where ms_uuid = '?'";

    const GET_OPTION_ID_BY_UUID = "SELECT option_id from oc_ms_samopek_option where ms_variant_uuid = '?'";

    const GET_PRODUCT_BY_PRODUCT_ID = "SELECT * from oc_product where product_id = ?";

    const GET_PRODUCT_PRICE_BY_PRODUCT_ID = "SELECT price from oc_product where product_id = ?";

    const GET_OPTION_VALUE_ID_BY_NAME = "select option_value_id from oc_option_value_description where name = '?'";

    const GET_PRODUCT_OPTION = "select product_option_id from oc_product_option where product_id = ? and option_id = ?";

    const GET_SEO_URL_BY_QUERY = "SELECT * from oc_seo_url where query = '?'";

    const GET_VERSION_BY_PRODUCT_ID = "SELECT * from oc_ms_samopek_product where product_id = ?";

    const GET_PRODUCTS_VERSIONS = "SELECT ms_id, ms_version from oc_ms_samopek_product";

    const GET_CATEGORY_ID_BY_UUID = "SELECT category_id from oc_ms_samopek_category where ms_group_uuid = '?'";

    const GET_CATEGORY_ID_TO_PARENT_CATEGORY_ID =
        "SELECT cat.category_id, sam_cat.category_id as parent_category_id 
         FROM oc_category cat
         LEFT OUTER JOIN oc_ms_samopek_category sam_cat
         ON (cat.image = sam_cat.ms_group_uuid)";

    // INSERTs
    const INSERT_INTO_PRODUCT =
        "INSERT INTO oc_product
         SET model = '?',
             sku = '',
             upc = '',
             ean = '',
             jan = '',
             isbn = '',
             mpn = '',
             location = '',
             quantity = ?,
             image = '?',
             minimum = '0',
             subtract = '1',
             stock_status_id = '5',
             date_available = '2019-01-01',
             manufacturer_id = '0',
             shipping = '1',
             price = ?,
             points = '0',
             weight = ?,
             weight_class_id = '0',
             length = '0',
             width = '0',
             height = '0',
             length_class_id = '0',
             status = '1',
             tax_class_id = '0',
             sort_order = '1',
             date_added = '?',
             date_modified = NOW()";

    const INSERT_INTO_PRODUCT_DESCRIPTION =
        "INSERT INTO oc_product_description
         SET product_id = ?,
             language_id = 1,
             name = '?',
             description = '?',
             tag = '',
             meta_title = '?',
             meta_description = '',
             meta_keyword = ''";

    const INSERT_INTO_MS_SAMOPEK_PRODUCT =
        "INSERT INTO oc_ms_samopek_product 
         SET product_id = ?,
             ms_id = ?,
             ms_uuid = '?',
             ms_version = ?";

    const INSERT_INTO_PRODUCT_TO_STORE =
        "INSERT INTO oc_product_to_store 
         SET product_id = ?,
             store_id = 0";

    const INSERT_INTO_PRODUCT_TO_CATEGORY =
        "INSERT INTO oc_product_to_category 
         SET product_id = ?,
             category_id = ?";

    const INSERT_INTO_CATEGORY =
        "INSERT INTO oc_category
         SET image = '?',
             parent_id = 0,
             top = 0,
             `column` = 1,
             sort_order = 0,
             status = 1,
             date_added = NOW(),
             date_modified = NOW()";

    const INSERT_INTO_CATEGORY_DESCRIPTION =
        "INSERT INTO oc_category_description
         SET category_id = ?,
             language_id = 1,
             name = '?',
             description = '',
             meta_title = '?',
             meta_description = '',
             meta_keyword = ''";

    const INSERT_INTO_CATEGORY_TO_STORE =
        "INSERT INTO oc_category_to_store 
         SET category_id = ?,
             store_id = 0";

    const INSERT_INTO_CATEGORY_PATH =
        "INSERT INTO oc_category_path 
         SET category_id = ?,
             path_id = ?,
             level = ?";

    const INSERT_INTO_MS_SAMOPEK_CATEGORY =
        "INSERT INTO oc_ms_samopek_category 
         SET category_id = ?,
             ms_group_uuid = '?',
             ms_version = '?'";

    const INSERT_INTO_SEO_URL =
        "INSERT INTO oc_seo_url 
         SET store_id = 0,
             language_id = 1,
             query = '?',
             keyword = '?'";

    const INSERT_INTO_OPTION =
        "INSERT INTO oc_option
         SET type = 'radio',
             sort_order = 1";

    const INSERT_INTO_OPTION_DESCRIPTION =
        "INSERT INTO oc_option_description
         SET option_id = ?,
             language_id = 1,
             name = '?'";

    const INSERT_INTO_OPTION_VALUE =
        "INSERT INTO oc_option_value
         SET option_id = ?,
             image = '',
             sort_order = 1";

    const INSERT_INTO_OPTION_VALUE_DESCRIPTION =
        "INSERT INTO oc_option_value_description
         SET option_value_id = ?,
             language_id = 1,
             option_id = ?,
             name = '?'";

    const INSERT_INTO_MS_SAMOPEK_OPTION =
        "INSERT INTO oc_ms_samopek_option
         SET option_id = ?,
             ms_variant_uuid = '?'";

    const INSERT_INTO_PRODUCT_OPTION =
        "INSERT INTO oc_product_option
         SET product_id = ?,
             option_id = ?,
             value = '',
             required = 1";

    const INSERT_INTO_PRODUCT_OPTION_VALUE =
        "INSERT INTO oc_product_option_value
         SET product_option_id = ?,
             product_id = ?,
             option_id = ?,
             option_value_id = ?,
             quantity = ?,
             subtract = 1,
             price = ?,
             price_prefix = '+',
             points = 0,
             points_prefix = '+',
             weight = 0,
             weight_prefix = '+'";

    const INSERT_INTO_ATTRIBUTE_GROUP =
        "INSERT INTO oc_attribute_group
         SET sort_order = 1";

    const INSERT_INTO_ATTRIBUTE_GROUP_DESCRIPTION =
        "INSERT INTO oc_attribute_group_description
         SET attribute_group_id = ?, 
             language_id = 1,
             name = '?'";

    const INSERT_INTO_ATTRIBUTE =
        "INSERT INTO oc_attribute
         SET attribute_group_id = ?,
             sort_order = 1";

    const INSERT_INTO_ATTRIBUTE_DESCRIPTION =
        "INSERT INTO oc_attribute_description
         SET attribute_id = ?,
             language_id = 1,
             name = '?'";

    const INSERT_INTO_MS_ATTRIBUTES =
        "INSERT INTO oc_ms_samopek_attributes
         SET attribute_id = ?,
             ms_attribute_uuid = '?'";

    const INSERT_INTO_PRODUCT_ATTRIBUTE =
        "INSERT INTO oc_product_attribute
         SET product_id = ?,
             attribute_id = ?,
             language_id = 1,
             text = '?'";

    // UPDATEs
    const UPDATE_PRODUCT =
        "UPDATE oc_product
         SET price = ?,
             image = '?',
             quantity = ? 
         WHERE product_id = ?";

    const UPDATE_PRODUCT_QUANTITY =
        "UPDATE oc_product
         SET quantity = ? 
         WHERE model = ?";

    const UPDATE_PRODUCT_DESCRIPTION =
        "UPDATE oc_product_description
        SET name = '?',
            description = '?'  
        WHERE product_id = ?";

    const UPDATE_MS_SAMOPEK_PRODUCT =
        "UPDATE oc_ms_samopek_product
         SET ms_version = ?
         WHERE product_id = ?";

    const UPDATE_CATEGORY_PARENT =
        "UPDATE oc_category
         SET parent_id = ? 
         WHERE category_id = ?";

    const UPDATE_ATTRIBUTE_DESCRIPTION =
        "UPDATE oc_attribute_description
         SET name = '?' 
         WHERE language_id = 1
           AND attribute_id = ?";

    // DELETEs
    const DELETE_FROM_PRODUCT = "DELETE FROM oc_product where product_id = ?";

    const DELETE_FROM_PRODUCT_DESCRIPTION = "DELETE FROM oc_product_description where product_id = ?";

    const DELETE_FROM_PRODUCT_TO_STORE = "DELETE FROM oc_product_to_store where product_id = ?";

    const DELETE_FROM_MS_SAMOPEK_PRODUCT = "DELETE FROM oc_ms_samopek_product where product_id = ?";

    const DELETE_FROM_CATEGORY = "DELETE FROM oc_category where category_id = ?";

    const DELETE_FROM_CATEGORY_DESCRIPTION = "DELETE FROM oc_category_description where category_id = ?";

    const DELETE_FROM_CATEGORY_TO_STORE = "DELETE FROM oc_category_to_store where category_id = ?";

    const DELETE_PRODUCT_ATTRIBUTE = "DELETE FROM oc_product_attribute WhERE product_id = ?";

    const DELETE_ALL_FROM_PRODUCT = "TRUNCATE oc_product";

    const DELETE_ALL_FROM_PRODUCT_DESCRIPTION = "TRUNCATE oc_product_description";

    const DELETE_ALL_FROM_MS_SAMOPEK_PRODUCT = "TRUNCATE oc_ms_samopek_product";

    const DELETE_ALL_FROM_PRODUCT_TO_STORE = "TRUNCATE oc_product_to_store";

    const DELETE_ALL_FROM_PRODUCT_TO_CATEGORY = "TRUNCATE oc_product_to_category";

    const DELETE_ALL_FROM_CATEGORY = "TRUNCATE oc_category";

    const DELETE_ALL_FROM_CATEGORY_DESCRIPTION = "TRUNCATE oc_category_description";

    const DELETE_ALL_FROM_CATEGORY_TO_STORE = "TRUNCATE oc_category_to_store";

    const DELETE_ALL_FROM_MS_SAMOPEK_CATEGORY = "TRUNCATE oc_ms_samopek_category";

    const DELETE_ALL_FROM_CATEGORY_PATH = "TRUNCATE oc_category_path";

    const DELETE_ALL_FROM_CATEGORY_TO_LAYOUT = "TRUNCATE oc_category_to_layout";

    const DELETE_ALL_FROM_MS_SAMOPEK_OPTION = "TRUNCATE oc_ms_samopek_option";

    const DELETE_ALL_FROM_OPTION = "TRUNCATE oc_option";

    const DELETE_ALL_FROM_OPTION_DESCRIPTION = "TRUNCATE oc_option_description";

    const DELETE_ALL_FROM_OPTION_VALUE = "TRUNCATE oc_option_value";

    const DELETE_ALL_FROM_OPTION_VALUE_DESCRIPTION = "TRUNCATE oc_option_value_description";

    const DELETE_ALL_FROM_PRODUCT_OPTION = "TRUNCATE oc_product_option";

    const DELETE_ALL_FROM_PRODUCT_OPTION_VALUE = "TRUNCATE oc_product_option_value";

    const DELETE_ALL_FROM_SEO_URL = "TRUNCATE oc_seo_url";
}
?>