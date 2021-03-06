<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jaichhorn
 * Date: 05.02.13
 * Time: 11:20
 * To change this template use File | Settings | File Templates.
 */
class Test_Data
{
    const IMAGE = "sampleimage.jpg";

    const DOCUMENT = "sampledocument.txt";

    const HOTSPOT_IMAGE = "hotspot.jpg";

    private static function createRandomProperties() {
        $properties = array();

            // object property
        $property = new Property();
        $property->setType("object");
        $property->setName("object");
        $property->setInheritable(true);
        $properties["bla"] = $property;
    }

    private function getObjectList($condition = null) {
        $list = new Object_List();
        $list->setOrderKey("o_id");
        $list->setCondition($condition);
        $objects = $list->load();
        return $objects;
    }

    public static function fillInput($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter("content" . $seed);
    }

    public static function assertInput($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = "content" . $seed;
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillNumber($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter(123 + $seed);
    }

    public static function assertNumber($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = "123" + $seed;
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillTextarea($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter("sometext<br>" . $seed);
    }

    public static function assertTextarea($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = "sometext<br>" . $seed;
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillHref($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $objects = self::getObjectList();
        $object->$setter($objects[0]);
    }

    public static function assertHref($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $objects = self::getObjectList();
        $expected = $objects[0];

        if ($value != $expected) {
            print("   expected " . $expected->getId() . " but was " . $value->getId());
            return false;
        }
        return true;
    }


    public static function fillMultihref($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $objects = self::getObjectList();
        $objects = array_slice($objects,0,4);

        $object->$setter($objects);
    }

    public static function assertMultihref($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $objects = self::getObjectList();
        $expectedArray = array_slice($objects,0,4);

        if (count($expectedArray) != count($value)) {
            print("count is different  " . count($expectedArray) . " != " . count($value) . "\n");
            return false;
        }

        for ($i = 0; $i < count($expectedArray); $i++) {
            if ($value[$i] != $expectedArray[$i]) {
                print("   expected " . $expectedArray[$i]->getId() . " but was " . $value[$i]->getId());
                return false;
            }
        }
        return true;
    }




    public static function fillSlider($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter(7 + ($seed % 3));
    }

    public static function assertSlider($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = 7 + ($seed % 3);
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillImage($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);

        $asset = Asset::getByPath("/". self::IMAGE);
        if (!$asset) {
            $asset = Test_Tool::createImageAsset("", null, false);
            $asset->setFilename(self::IMAGE);
            $asset->save();
        }

        $object->$setter($asset);
    }

    public static function assertImage($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = Asset::getByPath("/" . self::IMAGE);
        if ($expected != $value) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    private static function createHotspots() {
        $result = array();
        $hotspot = new stdClass();
        $hotspot->name = "hotspot1";
        $hotspot->width = "10";
        $hotspot->height = "20";
        $hotspot->top  = "30";
        $hotspot->left = "40";
        $result[] = $hotspot;
        $hotspot->width = "10";
        $hotspot->height = "50";
        $hotspot->top  = "20";
        $hotspot->left = "40";
        $result[] = $hotspot;
        return $result;
    }

    public static function fillHotspotImage($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);

        $asset = Asset::getByPath("/". self::HOTSPOT_IMAGE);
        if (!$asset) {
            $asset = Test_Tool::createImageAsset("", null, false);
            $asset->setFilename(self::HOTSPOT_IMAGE);
            $asset->save();
        }

        $hotspots = self::createHotspots();
        $hotspotImage = new Object_Data_Hotspotimage($asset, $hotspots);
        $object->$setter($hotspotImage);
    }

    public static function assertHotspotImage($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $hotspots = $value->getHotspots();
        if (count($hotspots)  != 2) {
            print("hotspot count is " . count($hotspots));
            return false;
        }
        $asset = Asset::getByPath("/" . self::HOTSPOT_IMAGE);
        $hotspots = self::createHotspots();
        $expected = new Object_Data_Hotspotimage($asset, $hotspots);

        $value = Test_Tool::createAssetComparisonString($value->getImage);
        $expected = Test_Tool::createAssetComparisonString($expected->getImage);

        if ($expected != $value) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillLanguage($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter("de");
    }

    public static function assertLanguage($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = "de";
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillCountry($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter("AU");
    }

    public static function assertCountry($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = "AU";
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillDate($object, $field, $seed = 1) {
    $setter = "set" . ucfirst($field);
    $object->$setter($date = new Pimcore_Date("2000-12-24", "yyyy-MM-dd"));
}

    public static function assertDate($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = new Pimcore_Date("2000-12-24", "yyyy-MM-dd");
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillSelect($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter(1 + ($seed % 2));
    }

    public static function assertSelect($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = 1 + ($seed % 2);
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillMultiSelect($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter(array("1", "2"));
    }

    public static function assertMultiSelect($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = array("1", "2");
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillUser($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);

        $username = "unittestdatauser" . $seed;
        $user = User::getByName($username);

        if (!$user) {
            $user = User::create(array(
                "parentId" => 0,
                "username" => $username,
                "password" => Pimcore_Tool_Authentication::getPasswordHash($username, $username),
                "active" => true
            ));
            $user->setAdmin(true);
            $user->save();
        }

        $object->$setter($user->getId());
    }

    public static function assertUser($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $user = User::getByName("unittestdatauser" . $seed);
        $expected = $user->getId();
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillCheckbox($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter(($seed % 2) == true);
    }

    public static function assertCheckbox($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = ($seed % 2) == true;
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillTime($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter("06:4" . $seed % 10);
    }

    public static function assertTime($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = "06:4" . $seed % 10;
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillWysiwyg($object, $field, $seed = 1) {
        self::fillTextarea($object, $field, $seed);
    }

    public static function assertWysiwyg($object, $field, $seed = 1) {
        return self::assertTextarea($object, $field, $seed);
    }

    public static function fillPassword($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter("sEcret$%!" . $seed);
    }

    public static function assertPassword($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        // it is intended that no password is sent
        $expected = null;
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value . "\n");
            var_dump($value);
            return false;
        }
        return true;
    }

    public static function fillCountryMultiSelect($object, $field, $seed = 1) {
    $setter = "set" . ucfirst($field);
    $object->$setter(array("1", "2"));
}

    public static function assertCountryMultiSelect($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = array("1", "2");
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillLanguageMultiSelect($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $object->$setter(array("1", "2"));
    }

    public static function assertLanguageMultiSelect($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $expected = array("1", "3");
        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillGeopoint($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);

        $longitude = 2.2008440814678;
        $latitude = 102.25112915039;
        $point = new Object_Data_Geopoint($longitude,$latitude);
        $object->$setter($point);
    }

    public static function assertGeopoint($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();

        $longitude = 2.2008440814678;
        $latitude = 102.25112915039;
        $expected = new Object_Data_Geopoint($longitude,$latitude);

        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillGeobounds($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $bounds = new Object_Data_Geobounds(new Object_Data_Geopoint(150.96588134765625, -33.704920213014425), new Object_Data_Geopoint(150.60333251953125, -33.893217379440884));
        $object->$setter($bounds);
    }

    public static function assertGeobounds($object, $field, $comparisonObject, $seed = 1) {
        $fd = $object->getClass()->getFieldDefinition($field);
        $value = Test_Tool::getComparisonDataForField($field, $fd, $object);
        $expected = Test_Tool::getComparisonDataForField($field, $fd, $comparisonObject);


        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillGeopolygon($object, $field, $seed = 1) {
    $setter = "set" . ucfirst($field);
    $polygon  = array(new Object_Data_Geopoint(150.54428100585938, -33.464671118242684), new Object_Data_Geopoint(150.73654174804688, -33.913733814316245), new Object_Data_Geopoint(151.2542724609375, -33.9946115848146));
    $object->$setter($polygon);
}

    public static function assertGeopolygon($object, $field, $comparisonObject, $seed = 1) {
        $fd = $object->getClass()->getFieldDefinition($field);
        $value = Test_Tool::getComparisonDataForField($field, $fd, $object);
        $expected = Test_Tool::getComparisonDataForField($field, $fd, $comparisonObject);

        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillTable($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $tabledata  = array(array("eins", "zwei", "drei"), array($seed, 2, 3), array("a", "b", "c"));
        $object->$setter($tabledata);
    }

    public static function assertTable($object, $field, $comparisonObject, $seed = 1) {
        $fd = $object->getClass()->getFieldDefinition($field);
        $value = Test_Tool::getComparisonDataForField($field, $fd, $object);
        $expected = Test_Tool::getComparisonDataForField($field, $fd, $comparisonObject);

        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value . "\n");
            return false;
        }
        return true;
    }


    public static function fillLink($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);


        $doc = Document::getByPath("/" . self::DOCUMENT . $seed);

        if (!$doc) {
            $doc = Test_Tool::createEmptyDocumentPage(null, false);
            $doc->setProperties(self::createRandomProperties());
            $doc->setKey(self::DOCUMENT . $seed);
            $doc->setParentId(1);
            $doc->save();
        }

        $object->$setter("content" . $seed);
    }

    public static function assertLink($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();

        $expected = Document::getByPath("/" . self::DOCUMENT . $seed);

        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    private static function getStructuredTableData($seed = 1) {
        $data['row1']['col1'] = 1 + $seed;
        $data['row2']['col1'] = 2 + $seed;
        $data['row3']['col1'] = 3 + $seed;

        $data['row1']['col2'] = "text_a_" . $seed;
        $data['row2']['col2'] = "text_b_" . $seed;
        $data['row3']['col2'] = "text_c_" . $seed;
    }




    public static function fillStructuredtable($object, $field, $comparisonObject, $seed = 1) {
        $setter = "set" . ucfirst($field);

        $data = new Object_Data_StructuredTable();
        $tabledata = self::getStructuredTableData($seed);
        $data->setData($tabledata);
        $object->$setter($data);
    }

    public static function assertStructuredTable($object, $field, $comparisonObject, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();

        $fd = $object->getClass()->getFieldDefinition($field);
        $value = Test_Tool::getComparisonDataForField($field, $fd, $object);
        $expected = Test_Tool::getComparisonDataForField($field, $fd, $comparisonObject);

        if ($value != $expected) {
            print("   expected " . $expected . " but was " . $value);
            return false;
        }
        return true;
    }

    public static function fillObjects($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $objects = self::getObjectList("o_type = 'object'");
        $objects = array_slice($objects,0,4);

        $object->$setter($objects);
    }

    public static function assertObjects($object, $field, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();
        $objects = self::getObjectList("o_type = 'object'");
        $expectedArray = array_slice($objects,0,4);

        if (count($expectedArray) != count($value)) {
            print("count is different  " . count($expectedArray) . " != " . count($value) . "\n");
            return false;
        }

        for ($i = 0; $i < count($expectedArray); $i++) {
            if ($value[$i] != $expectedArray[$i]) {
                print("   expected " . $expectedArray[$i]->getId() . " but was " . $value[$i]->getId());
                return false;
            }
        }
        return true;
    }

    public static function fillObjectsWithMetadata($object, $field, $seed = 1) {
        $setter = "set" . ucfirst($field);
        $objects = self::getObjectList("o_type = 'object'");
        $objects = array_slice($objects,0,4);

        $metaobjects = array();
        foreach ($objects as $o) {
            $mo = new Object_Data_ObjectMetadata($field, array("meta1", "meta2"), $o);
            $mo->setMeta1("value1" . $seed);
            $mo->setMeta2("value2" . $seed);
            $metaobjects[] = $mo;
        }

        $object->$setter($metaobjects);
    }

    public static function assertObjectsWithMetadata($object, $field, $comparisonObject, $seed = 1) {
        $getter = "get" . ucfirst($field);
        $value = $object->$getter();

        $fd = $object->getClass()->getFieldDefinition($field);
        $valueForField = Test_Tool::getComparisonDataForField($field, $fd, $object);
        $expected = Test_Tool::getComparisonDataForField($field, $fd, $comparisonObject);

        if ($valueForField != $expected) {
            print("   expected " . $expected . " but was " . $valueForField);
            return false;
        }

        $rel1 = $value[0];
        $meta = $rel1->getMeta1();
        if ($meta != ("value1".$seed))
        {
            print("sample value does not match");
            return false;
        }
        return true;
    }

}
