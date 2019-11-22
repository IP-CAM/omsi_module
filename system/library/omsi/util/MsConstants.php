<?php
    // product
    define("P_DESC", "description");
    define("P_CODE", "code");

    // group
    define("UUID", "id");

    // salePrices section
    define("P_SALE_PRICES", "salePrices");
    define("P_WEIGHT", "weight");

    // image
    define("P_IMAGE", "image");
    define("P_IMAGE_FILENAME", "filename");

    // assortment
    define("ASS_QUANTITY", "quantity");

     // Common
    define("HREF", "href");
    define("META", "meta");
    define("META_TYPE", "type");
    define("META_TYPE_PRODUCT", "product");
    define("META_TYPE_VARIANT", "variant");
    define("VALUE", "value");
    define("PRODUCT_FOLDER", "productFolder");
    define("MS_VERSION", "version");
    define("NAME", "name");
    define("UPDATED", "updated");
    define("ATTRIBUTES", "attributes");

    // URLs
    define("URL_BASE", "https://online.moysklad.ru/api/remap/1.1");
    define("URL_GET_PRODUCT", "/entity/product");
    define("URL_GET_PRODUCT_METADATA", "/entity/product/metadata");
    define("URL_GET_UPDATED_PRODUCT", "/entity/product");
    define("URL_GET_ASSORTMENT", "/entity/assortment");
    define("URL_GET_GROUP", "/entity/productfolder");
    define("URL_GET_VARIANTS", "/entity/variant");
    define("URL_GET_ALL_VARIANTS_TYPES", "/entity/variant/metadata");
    define("URL_GET_CUSTOMER", "/entity/counterparty");
    define("URL_GET_ORGANIZATION", "/entity/organization");
    define("URL_GET_CUSTOMER_ORDER", "/entity/customerorder");

    // URL params
    define("URL_PARAM_LIMIT", "limit=100");
    define("URL_PARAM_OFFSET", "offset=");
    define("URL_PARAM_UPDATED_FROM", "updatedFrom=");
    define("URL_PARAM_FILTER", "filter=");
    define("URL_PARAM_CODE", "code=");
    define("URL_PARAM_SEARCH", "search=");
?>