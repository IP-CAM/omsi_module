<?php
class ParseUtils {

    public static function getParentCategoryUuid($row) {
        if (array_key_exists(PRODUCT_FOLDER, $row)) {
            $href = $row[PRODUCT_FOLDER][META][HREF];
            return self::getLastSegment($href);
        }
        return null;
    }

    public static function getProductUuidByOption($option) {
        if (array_key_exists(META_TYPE_PRODUCT, $option)) {
            $href = $option[META_TYPE_PRODUCT][META][HREF];
            return self::getLastSegment($href);
        }
        return null;
    }

    private static function getLastSegment($href) {
        $res = explode("/", $href);
        return end($res);
    }
}
?>