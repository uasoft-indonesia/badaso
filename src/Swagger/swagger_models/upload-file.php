<?php

/**
 * @OA\Post(
 *      path="/v1/file/upload/lfm",
 *      operationId="uploadFile",
 *      tags={"upload file"},
 *      summary="Upload File",
 *      description="Upload File",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                @OA\Property(
 *                    description="/shares use if you access storage public users, /{user_id} use if you access storage private users",
 *                    property="working_dir",
 *                    type="string",
 *                    example="/shares"
 *                ),
 *                @OA\Property(
 *                    property="upload",
 *                    type="string",
 *                    format="binary",
 *                ),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Get(
 *      path="/v1/file/browse/lfm?workingDir={workingDir}",
 *      operationId="browseFiles",
 *      tags={"upload file"},
 *      summary="Browse File Shares",
 *      description="Returns list of File Shares",
 *      @OA\Parameter(
 *          name="workingDir",
 *          required=true,
 *          in="path",
 *          description="/shares use if you access storage public users, /{user_id} use if you access storage private users",
 *          @OA\Schema(
 *              type="string",
 *              example="/shares",
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={
 *          {"bearerAuth": {}}
 *      }
 * )
 */

/**
 * @OA\Get(
 *      path="/v1/file/delete/lfm?workingDir={workingDir}&items[]={items}",
 *      operationId="deleteFile",
 *      tags={"upload file"},
 *      summary="Delete File Shares",
 *      description="Delete File In Shares",
 *      @OA\Parameter(
 *          name="workingDir",
 *          required=true,
 *          in="path",
 *          description="/shares use if you access storage public users, /{user_id} use if you access storage private users",
 *          @OA\Schema(
 *              type="string",
 *              example="/shares"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="items",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(
 *                  type="string",
 *                  example="http://localhost:8000/storage/1kb-thumb.png"
 *              ),
 *          )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *      security={
 *          {"bearerAuth": {}}
 *      }
 * )
 */
