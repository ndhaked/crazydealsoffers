<?php

namespace App\Models\Spatie;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class PermissionGroups extends Model
{
    use SoftDeletes;
    use Sluggable;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    protected $fillable = ['slug','name','description'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate'=>false
            ]
        ];
    }

    public function createPermissionGroup($string='')
    {
        if($string){
            $data = explode('.',$string);
            array_pop($data);
            $slug  = implode('.',$data);
            $gName = implode(' ',$data);
            $group = static::where('slug',$slug)->count();
            if($group == 0)
            {
                if($slug){
                   static::create([
                        'slug' => trim($slug),    
                        'name' => ucwords($gName)
                    ]); 
                }
            }
            return $slug;
        }
    }

    public function createPermissionGroupBySeeder($name)
    {
        if($name){
            $group = static::where('name',$name)->count();
            if($group == 0)
            {
                if($name){
                   static::create([
                        'name' => ucwords($gName)
                    ]); 
                }
            }
            return $name;
        }
    }
}