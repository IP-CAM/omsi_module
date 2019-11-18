<?php
class Product {
    private $product_id;

    // MS
    private $uuid;

    // oc_product
    private $model;
    private $sku;
    private $upc;
    private $ean;
    private $jan;
    private $isbn;
    private $mpn;
    private $location;
    private $quantity;
    private $image;
    private $minimum;
    private $subtract;
    private $stock_status_id;
    private $date_available;
    private $manufacturer_id;
    private $shipping;
    private $price;
    private $points;
    private $weight;
    private $weight_class_id;
    private $length;
    private $width;
    private $height;
    private $length_class_id;
    private $status;
    private $tax_class_id;
    private $sort_order;
    private $date_added;
    private $date_modified;

    private $attributes;

    // oc_product_description
    private $language_id;
    private $name;
    private $description;
    private $tag;
    private $meta_title;
    private $meta_description;
    private $meta_keyword;

    // oc_product_to_store
    private $store_id = 0;

    // oc_product_to_category
    private $category_id;

    // oc_ms_samopek_products
    private $version;

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * @param mixed $product_id
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * @return mixed
     */
    public function getLanguageId()
    {
        return $this->language_id;
    }

    /**
     * @param mixed $language_id
     */
    public function setLanguageId($language_id)
    {
        $this->language_id = $language_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title)
    {
        $this->meta_title = $meta_title;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description)
    {
        $this->meta_description = $meta_description;
    }

    /**
     * @return mixed
     */
    public function getMetaKeyword()
    {
        return $this->meta_keyword;
    }

    /**
     * @param mixed $meta_keyword
     */
    public function setMetaKeyword($meta_keyword)
    {
        $this->meta_keyword = $meta_keyword;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->store_id;
    }

    /**
     * @param int $store_id
     */
    public function setStoreId($store_id)
    {
        $this->store_id = $store_id;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * @param mixed $upc
     */
    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    /**
     * @return mixed
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * @param mixed $ean
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    /**
     * @return mixed
     */
    public function getJan()
    {
        return $this->jan;
    }

    /**
     * @param mixed $jan
     */
    public function setJan($jan)
    {
        $this->jan = $jan;
    }

    /**
     * @return mixed
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param mixed $isbn
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * @return mixed
     */
    public function getMpn()
    {
        return $this->mpn;
    }

    /**
     * @param mixed $mpn
     */
    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @param mixed $minimum
     */
    public function setMinimum($minimum)
    {
        $this->minimum = $minimum;
    }

    /**
     * @return mixed
     */
    public function getSubtract()
    {
        return $this->subtract;
    }

    /**
     * @param mixed $subtract
     */
    public function setSubtract($subtract)
    {
        $this->subtract = $subtract;
    }

    /**
     * @return mixed
     */
    public function getStockStatusId()
    {
        return $this->stock_status_id;
    }

    /**
     * @param mixed $stock_status_id
     */
    public function setStockStatusId($stock_status_id)
    {
        $this->stock_status_id = $stock_status_id;
    }

    /**
     * @return mixed
     */
    public function getDateAvailable()
    {
        return $this->date_available;
    }

    /**
     * @param mixed $date_available
     */
    public function setDateAvailable($date_available)
    {
        $this->date_available = $date_available;
    }

    /**
     * @return mixed
     */
    public function getManufacturerId()
    {
        return $this->manufacturer_id;
    }

    /**
     * @param mixed $manufacturer_id
     */
    public function setManufacturerId($manufacturer_id)
    {
        $this->manufacturer_id = $manufacturer_id;
    }

    /**
     * @return mixed
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param mixed $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getWeightClassId()
    {
        return $this->weight_class_id;
    }

    /**
     * @param mixed $weight_class_id
     */
    public function setWeightClassId($weight_class_id)
    {
        $this->weight_class_id = $weight_class_id;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getLengthClassId()
    {
        return $this->length_class_id;
    }

    /**
     * @param mixed $length_class_id
     */
    public function setLengthClassId($length_class_id)
    {
        $this->length_class_id = $length_class_id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTaxClassId()
    {
        return $this->tax_class_id;
    }

    /**
     * @param mixed $tax_class_id
     */
    public function setTaxClassId($tax_class_id)
    {
        $this->tax_class_id = $tax_class_id;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sort_order;
    }

    /**
     * @param mixed $sort_order
     */
    public function setSortOrder($sort_order)
    {
        $this->sort_order = $sort_order;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param mixed $date_added
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
    }

    /**
     * @return mixed
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * @param mixed $date_modified
     */
    public function setDateModified($date_modified)
    {
        $this->date_modified = $date_modified;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}
?>