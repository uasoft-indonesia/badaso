<?php

/**
 * @OA\Post(
 *      path="/v1/auth/forgot-password",
 *      operationId="forgotPassword",
 *      tags={"auth"},
 *      summary="Forgot Password",
 *      description="Send forgot password request",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/forgot-password-verify",
 *      operationId="forgotPasswordVerify",
 *      tags={"auth"},
 *      summary="Verify Forgot Password Token",
 *      description="Verify the forgot password token sent to the email",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     example="123456"
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/login",
 *      operationId="login",
 *      tags={"auth"},
 *      summary="Login Authentication",
 *      description="Log in the user",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="remember",
 *                     type="boolean",
 *                     example="false"
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/logout",
 *      operationId="logout",
 *      tags={"auth"},
 *      summary="Logout",
 *      description="Log out the current user",
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/re-request-verification",
 *      operationId="reRequestVerification",
 *      tags={"auth"},
 *      summary="Resend the verification token",
 *      description="Request the server to resend the verification token",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/refresh-token",
 *      operationId="refreshToken",
 *      tags={"auth"},
 *      summary="Refresh Token for Authentication",
 *      description="Request the jwt refresh token",
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/register",
 *      operationId="register",
 *      tags={"auth"},
 *      summary="User Registration",
 *      description="Register the user",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     example="John Doe"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="username",
 *                     type="string",
 *                     example="johndoe"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string",
 *                     example=""
 *                 ),
 *                 @OA\Property(
 *                     property="passwordConfirmation",
 *                     type="string",
 *                     example=""
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/reset-password",
 *      operationId="resetPassword",
 *      tags={"auth"},
 *      summary="User Reset Password",
 *      description="Send reset password request",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     example=""
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *
 *      security={{"bearerAuth" : {}}}
 * )
 */

/**
 * @OA\Post(
 *      path="/v1/auth/verify",
 *      operationId="getUser",
 *      tags={"auth"},
 *      summary="Get User Info",
 *      description="Get the current user information",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string",
 *                     example="johndoe@gmail.com"
 *                 ),
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     example=""
 *                 ),
 *             )
 *         )
 *      ),
 *      @OA\Response(response=200, description="Successful operation"),
 *      @OA\Response(response=400, description="Bad request"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=402, description="Payment Required"),
 *
 *      security={{"bearerAuth" : {}}}
 * )
 */
