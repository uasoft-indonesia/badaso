<?php

/**
 * @OA\Get(
 *      path="/v1/users",
 *      operationId="browseUsers",
 *      tags={"user"},
 *      summary="Browse Users",
 *      description="Browse Users",
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Get(
 *      path="/v1/users/read?id={id}",
 *      operationId="readUsers",
 *      tags={"user"},
 *      summary="Read User",
 *      description="Read User",
 *      @OA\Parameter(
 *          name="id",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer",
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/users/add",
 *      operationId="addUser",
 *      tags={"user"},
 *      summary="Add User",
 *      description="Add User",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                @OA\Property(
 *                    property="id",
 *                    type="integer",
 *                    example="1"
 *                ),
 *                @OA\Property(
 *                    property="name",
 *                    type="string",
 *                    example="johndoe"
 *                ),
 *                @OA\Property(
 *                    property="email",
 *                    type="string",
 *                    example="johndoe@gmail.com"
 *                ),
 *                @OA\Property(
 *                    property="password",
 *                    type="string",
 *                    example="1234567"
 *                ),
 *                @OA\Property(
 *                    property="additional_info",
 *                    type="string",
 *                    example=""
 *                ),
 *                @OA\Property(
 *                    property="avatar",
 *                    type="string",
 *                    example="data:image/png;base64,iVBORw0KGgoAAAAN..."
 *                )
 *              )
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Put(
 *      path="/v1/users/edit",
 *      operationId="editUser",
 *      tags={"user"},
 *      summary="Edit User",
 *      description="Edit User",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                @OA\Property(
 *                    property="id",
 *                    type="integer",
 *                    example="1"
 *                ),
 *                @OA\Property(
 *                    property="name",
 *                    type="string",
 *                    example="johndoe"
 *                ),
 *                @OA\Property(
 *                    property="email",
 *                    type="string",
 *                    example="johndoe@gmail.com"
 *                ),
 *                @OA\Property(
 *                    property="password",
 *                    type="string",
 *                    example="1234567"
 *                ),
 *                @OA\Property(
 *                    property="additional_info",
 *                    type="string",
 *                    example=""
 *                ),
 *                @OA\Property(
 *                    property="avatar",
 *                    type="string",
 *                    example="data:image/png;base64,iVBORw0KGgoAAAAN..."
 *                )
 *              )
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Delete(
 *      path="/v1/users/delete",
 *      operationId="deleteUser",
 *      tags={"user"},
 *      summary="Delete User",
 *      description="Delete User",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                @OA\Property(
 *                    property="id",
 *                    type="integer",
 *                    example="1"
 *                )
 *              )
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Delete(
 *      path="/v1/users/delete-multiple",
 *      operationId="deleteMultipleUser",
 *      tags={"user"},
 *      summary="Delete Multiple User",
 *      description="Delete Multiple User",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                @OA\Property(
 *                    property="ids",
 *                    type="integer",
 *                    example="1,2"
 *                )
 *              )
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */
