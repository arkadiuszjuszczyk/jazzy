<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(definition="Gnome", type="object", required={"name", "age", "strength"})
 */
class Gnome extends Model
{
    protected $fillable = [
        'name', 'age', 'strength', 'avatar'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * @var number
     * @SWG\Property()
     */
    public $id;

    /**
     * @var string
     * @SWG\Property()
     */
    public $name;

    /**
     * @var number
     * @SWG\Property()
     */
    public $age;

    /**
     * @var number
     * @SWG\Property()
     */
    public $strength;

    /**
     * @var string
     * @SWG\Property()
     */
    public $avatar;
}
