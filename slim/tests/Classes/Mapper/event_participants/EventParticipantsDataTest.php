<?php
namespace Tests\Classes\Mapper\EventParticipants;

use PHPUnit\Framework\TestCase;
use Classes\Mapper\EventParticipants;
use AspectMock\Test as test;
use Tests\Classes as Base;

class EventParticipantsDataTest extends Base\BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    protected function tearDown()
    {
        parent::tearDown();

        test::clean(); // 登録したテストダブルをすべて削除
    }

    // データ作成
    private $data = array(
        'id' => 'idxxx',
        'event_id' => 'event_idxxx',
        'member_id' => 'member_idxxx',
        'join_flag' => 'join_flagxxx',
        'new_flag' => 'new_flagxxx',
        'new_name' => 'new_namexxx',
        'new_gender' => 'new_genderxxx',
        'new_age' => 'new_agexxx',
        'created_at' => 'created_atxxx',
        'updated_at' => 'updated_atxxx',
        'display_name' => 'display_namexxx',
        'picture_url' => 'picture_urlxxx',
    );

    /**
     * @group data
     */
    public function test__construct(){

        // データ作成
        $object = new EventParticipants\EventParticipantsData($this->data);
        
        // プライベート変数取得
        $reflectionClass = new \ReflectionClass($object);
        $id = $reflectionClass->getProperty('id');
        $id->setAccessible(true);
        $event_id = $reflectionClass->getProperty('event_id');
        $event_id->setAccessible(true);
        $member_id = $reflectionClass->getProperty('member_id');
        $member_id->setAccessible(true);
        $join_flag = $reflectionClass->getProperty('join_flag');
        $join_flag->setAccessible(true);
        $new_flag = $reflectionClass->getProperty('new_flag');
        $new_flag->setAccessible(true);
        $new_name = $reflectionClass->getProperty('new_name');
        $new_name->setAccessible(true);
        $new_gender = $reflectionClass->getProperty('new_gender');
        $new_gender->setAccessible(true);
        $new_age = $reflectionClass->getProperty('new_age');
        $new_age->setAccessible(true);
        $created_at = $reflectionClass->getProperty('created_at');
        $created_at->setAccessible(true);
        $updated_at = $reflectionClass->getProperty('updated_at');
        $updated_at->setAccessible(true);
        $display_name = $reflectionClass->getProperty('display_name');
        $display_name->setAccessible(true);
        $picture_url = $reflectionClass->getProperty('picture_url');
        $picture_url->setAccessible(true);

        // id
        $idValue = $id->getValue($object);
        $this->assertEquals($idValue,$this->data['id']);

        // event_id
        $event_idValue = $event_id->getValue($object);
        $this->assertEquals($event_idValue,$this->data['event_id']);

        // member_id
        $member_idValue = $member_id->getValue($object);
        $this->assertEquals($member_idValue,$this->data['member_id']);

        // join_flag
        $join_flagValue = $join_flag->getValue($object);
        $this->assertEquals($join_flagValue,$this->data['join_flag']);

        // new_flag
        $new_flagValue = $new_flag->getValue($object);
        $this->assertEquals($new_flagValue,$this->data['new_flag']);

        // new_name
        $new_nameValue = $new_name->getValue($object);
        $this->assertEquals($new_nameValue,$this->data['new_name']);

        // new_gender
        $new_genderValue = $new_gender->getValue($object);
        $this->assertEquals($new_genderValue,$this->data['new_gender']);

        // new_age
        $new_ageValue = $new_age->getValue($object);
        $this->assertEquals($new_ageValue,$this->data['new_age']);

        // created_at
        $created_atValue = $created_at->getValue($object);
        $this->assertEquals($created_atValue,$this->data['created_at']);

        // updated_at
        $updated_atValue = $updated_at->getValue($object);
        $this->assertEquals($updated_atValue,$this->data['updated_at']);

        // display_name
        $display_nameValue = $display_name->getValue($object);
        $this->assertEquals($display_nameValue,$this->data['display_name']);

        // picture_url
        $picture_urlValue = $picture_url->getValue($object);
        $this->assertEquals($picture_urlValue,$this->data['picture_url']);

    }

    /**
     * @group data
     */
    public function testgetNewFlag(){

        $object = new EventParticipants\EventParticipantsData($this->data);
        $this->assertEquals($object->getNewFlag(),$this->data['new_flag']);

    }

    /**
     * @group data
     */
    public function testgetDisplayName(){

        $object = new EventParticipants\EventParticipantsData($this->data);
        $this->assertEquals($object->getDisplayName(),$this->data['display_name']);

    }

    /**
     * @group data
     */
    public function testgetPictureUrl(){

        $object = new EventParticipants\EventParticipantsData($this->data);
        $this->assertEquals($object->getPictureUrl(),$this->data['picture_url']);

    }

    /**
     * @group data
     */
    public function testgetNewName(){

        $object = new EventParticipants\EventParticipantsData($this->data);
        $this->assertEquals($object->getNewName(),$this->data['new_name']);

    }

    /**
     * @group data
     */
    public function testgetNewGender(){

        $object = new EventParticipants\EventParticipantsData($this->data);
        $this->assertEquals($object->getNewGender(),$this->data['new_gender']);

    }

    /**
     * @group data
     */
    public function testgetNewAge(){

        $object = new EventParticipants\EventParticipantsData($this->data);
        $this->assertEquals($object->getNewAge(),$this->data['new_age']);

    }
}