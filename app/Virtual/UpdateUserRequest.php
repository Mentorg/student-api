<?php

namespace App\Virtual;

/**
 * @OA\Schema(
 *      title="Update User request",
 *      description="Update User request body data",
 *      type="object",
 *      required={"name"}
 * )
 */

class UpdateUserRequest
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="Name of the new user",
     *      example="A nice user"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="description",
     *      description="Description of the new user",
     *      example="This is new user's description"
     * )
     *
     * @var string
     */
    public $description;

    /**
     * @OA\Property(
     *      title="author_id",
     *      description="Author's id of the new user",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $author_id;
}
