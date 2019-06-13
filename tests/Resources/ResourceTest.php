<?php

namespace CrCms\Foundation\Tests\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function testResourceFields()
    {
        $resource = new BaseResource(['f1' => '1', 'f2' => '2', 'f3' => 3, 'f4' => 'abc']);
        $result = $resource->fields(['f4'])->resolve(\Mockery::mock(Request::class));

        $this->assertEquals(true, is_array($result));
        $this->assertEquals('f4', $result['f4']);
    }

    public function testResourceScene()
    {
        $resource = new BaseResource(['f1' => '1', 'f2' => '2', 'f3' => 3]);
        $result = $resource->scene('scene1')->resolve(\Mockery::mock(Request::class));

        $this->assertEquals(true, is_array($result));
        $this->assertEquals('f4', $result['f4']);
        $this->assertEquals(3, $result['f3']);
        $this->assertEquals('2', $result['f2']);
    }

    public function testResourceNull()
    {
        $resource = new BaseResource(null);
        $result = $resource->resolve(\Mockery::mock(Request::class));
        $this->assertEquals(true, is_array($result));
    }

    public function testResourceCast()
    {
        $resource = new BaseResource(['f1' => '1', 'f2' => '2', 'f3' => 3, 'f4' => 'abc']);
        $result = $resource->fields(['f3'])->resolve(\Mockery::mock(Request::class));

        $this->assertEquals(true, is_string($result['f3']));
    }

    public function testResourceMakeEmpty()
    {
        $params = null;
        $result = BaseResource::whenMake($params);
        $this->assertEquals($params,$result);
    }

//    public function testCollectionFields()
//    {
//        $resource = new BaseCollection(
//            [
//                ['f1' => '1', 'f2' => '2', 'f3' => 3, 'f4' => 'abc'],
//                ['f1' => '11', 'f2' => '21', 'f3' => 31, 'f4' => 'abc1'],
//            ]
//        );
//        $result = $resource->fields(['f4'])->resolve(\Mockery::mock(Request::class));
//        dd($result);
//
//        $this->assertEquals(true, is_array($result));
//        $this->assertEquals('f4', $result['f4']);
//    }
}