---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://api.nknx.org/docs/collection.json)

<!-- END_INFO -->

#Address book

Endpoints for querying registered names in the NKN blockchain
<!-- START_a69a5997417a0be7eead750d29021835 -->
## Get name by address

Returns the name of a specific address

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/walletNames/{walletAddress}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/walletNames/{walletAddress}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
"christnknx"
```

### HTTP Request
`GET walletNames/{walletAddress}`


<!-- END_a69a5997417a0be7eead750d29021835 -->

<!-- START_d852ce3db5ae10199d25471b49fca7ff -->
## Get the notification settings for current user

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Returns all notification settings of the current logged in User

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/auth/user/notifications" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/user/notifications",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "user_id": 1,
    "nodeOffline": 0,
    "nodeOutdated": 0,
    "nodeStucked": 0,
    "weeklyMiningOutput": 0,
    "updated_at": "2018-12-30 17:12:22",
    "created_at": "2018-12-30 17:12:22",
    "id": 1
}
```

### HTTP Request
`GET auth/user/notifications`


<!-- END_d852ce3db5ae10199d25471b49fca7ff -->

<!-- START_aa34b2132ff352aaec450f63f6239eaf -->
## Update notification settings

Updates all notification settings of the current logged in User

> Example request:

```bash
curl -X PUT "https://api.nknx.org/auth/user/notifications" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/user/notifications",
    "method": "PUT",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "user_id": 1,
    "nodeOffline": 0,
    "nodeOutdated": 0,
    "nodeStucked": 0,
    "weeklyMiningOutput": 0,
    "updated_at": "2018-12-30 17:12:22",
    "created_at": "2018-12-30 17:12:22",
    "id": 1
}
```

### HTTP Request
`PUT auth/user/notifications`


<!-- END_aa34b2132ff352aaec450f63f6239eaf -->

#Addresses

Endpoints for address-based queries
<!-- START_a89f0be59ea88f06284dbeae93e6a314 -->
## Get single address

Returns a specific address with first/last transaction date and transaction count

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/addresses/{address}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/addresses/{address}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{}
```

### HTTP Request
`GET addresses/{address}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    address |  required  | A NKN wallet address

<!-- END_a89f0be59ea88f06284dbeae93e6a314 -->

#Auth and User management

APIs for managing users
<!-- START_06cb1b0688f24d603f49db812175a611 -->
## Register a user

Creates an initial user entity in the database and also starts verification process

> Example request:

```bash
curl -X POST "https://api.nknx.org/auth/register"     -d "email"="JudQpv8UpkMaowWL" \
    -d "name"="kJMnhZJy9OpeByX6" \
    -d "password"="ZQl2lvEMF81AXcFJ" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/register",
    "method": "POST",
    "data": {
        "email": "JudQpv8UpkMaowWL",
        "name": "kJMnhZJy9OpeByX6",
        "password": "ZQl2lvEMF81AXcFJ"
    },
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "ChrisT",
        "email": "test@nknx.org",
        "verified": false,
        "created_at": "2018-10-15 09:50:55",
        "updated_at": "2018-10-15 09:51:37"
    }
}
```

### HTTP Request
`POST auth/register`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address
    name | string |  required  | Username
    password | string |  required  | User password

<!-- END_06cb1b0688f24d603f49db812175a611 -->

<!-- START_963f2e2e63c9db0a58f11a781dd6e00b -->
## Verify email

Accepts a token provided in an eMail link and verifies the user entity

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/auth/verify/{token}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/verify/{token}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "Your e-mail is verified. You can now login."
}
```

### HTTP Request
`GET auth/verify/{token}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    token |  required  | The verification token

<!-- END_963f2e2e63c9db0a58f11a781dd6e00b -->

<!-- START_76b4985a785e9d8afbfd7e75db9b94ac -->
## Set new password

Sets a new password for a user from a provided token

> Example request:

```bash
curl -X POST "https://api.nknx.org/auth/reset/{token}"     -d "password"="L4IUR1HkhPcZbt0h" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/reset/{token}",
    "method": "POST",
    "data": {
        "password": "L4IUR1HkhPcZbt0h"
    },
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success",
    "msg": "Password changed successfully."
}
```

### HTTP Request
`POST auth/reset/{token}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    password | string |  required  | User password

<!-- END_76b4985a785e9d8afbfd7e75db9b94ac -->

<!-- START_6b7ce6cdce5c0920189fae9e9f6d7af5 -->
## Reset password

Creates a password reset mail and an entry in the database

> Example request:

```bash
curl -X POST "https://api.nknx.org/auth/reset"     -d "email"="OIugriie2TEZdRjR" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/reset",
    "method": "POST",
    "data": {
        "email": "OIugriie2TEZdRjR"
    },
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success",
    "data": ""
}
```

### HTTP Request
`POST auth/reset`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address

<!-- END_6b7ce6cdce5c0920189fae9e9f6d7af5 -->

<!-- START_44652685eefa8022f19b92a3ce78d990 -->
## Login

Logs a user in

> Example request:

```bash
curl -X POST "https://api.nknx.org/auth/login"     -d "email"="nHdrihYJglv3TnpL" \
    -d "password"="lmq44JCWWtTHF0fo" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/login",
    "method": "POST",
    "data": {
        "email": "nHdrihYJglv3TnpL",
        "password": "lmq44JCWWtTHF0fo"
    },
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success"
}
```

### HTTP Request
`POST auth/login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address
    password | string |  required  | User password

<!-- END_44652685eefa8022f19b92a3ce78d990 -->

<!-- START_b25bea9b921111447db41e83ca0f4564 -->
## User
Returns the current logged in User

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/auth/user" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/user",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "ChrisT",
        "email": "test@nknx.org",
        "verified": true,
        "created_at": "2018-10-15 09:50:55",
        "updated_at": "2018-10-15 09:51:37"
    }
}
```

### HTTP Request
`GET auth/user`


<!-- END_b25bea9b921111447db41e83ca0f4564 -->

<!-- START_98ba2cace067628a23af37136b61013b -->
## Logout
Logs a user out

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://api.nknx.org/auth/logout" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/logout",
    "method": "POST",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success",
    "msg": "Logged out successfully."
}
```

### HTTP Request
`POST auth/logout`


<!-- END_98ba2cace067628a23af37136b61013b -->

<!-- START_94ea921eeadd64682f0624c185109b70 -->
## Resend verification mail
Recreates VerifyUser entity and resends the verification mail

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/auth/resendVerification" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/resendVerification",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success",
    "data": ""
}
```

### HTTP Request
`GET auth/resendVerification`


<!-- END_94ea921eeadd64682f0624c185109b70 -->

<!-- START_dbefbaa243dab4807513d72c5d194cef -->
## Change a user

Changes the userdata based on the given values

> Example request:

```bash
curl -X POST "https://api.nknx.org/auth/changeUser"     -d "email"="gwREeciBCdceFxHg" \
    -d "name"="4orYjVRIme92Rtk1" \
    -d "password"="e1UuPu8R4SCSyNAk" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/changeUser",
    "method": "POST",
    "data": {
        "email": "gwREeciBCdceFxHg",
        "name": "4orYjVRIme92Rtk1",
        "password": "e1UuPu8R4SCSyNAk"
    },
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST auth/changeUser`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address
    name | string |  required  | Username
    password | string |  required  | User password

<!-- END_dbefbaa243dab4807513d72c5d194cef -->

<!-- START_e77c3e002818fc05b52243f5d31d9cc8 -->
## Refresh
Refreshes the current users session

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/auth/refresh" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/auth/refresh",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "status": "success"
}
```

### HTTP Request
`GET auth/refresh`


<!-- END_e77c3e002818fc05b52243f5d31d9cc8 -->

#Blocks

Endpoints for block-based queries
<!-- START_d7aa65f027eef1b76bf3c9cc7b116681 -->
## Get single block by height/hash

Returns a specific block based on the height or block hash

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/blocks/{block_id}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/blocks/{block_id}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{}
```

### HTTP Request
`GET blocks/{block_id}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    block_id |  required  | Id of the resource
    withoutTransactions |  optional  | add block transactions to result

<!-- END_d7aa65f027eef1b76bf3c9cc7b116681 -->

<!-- START_f0d1da991c4ef405b9f00f2ce8d8fb72 -->
## Get transactions

Returns all transactions of a specific block based on the height or block hash

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/blocks/{block_id}/transactions" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/blocks/{block_id}/transactions",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "message": "Call to a member function transactions() on null",
    "exception": "Symfony\\Component\\Debug\\Exception\\FatalThrowableError",
    "file": "\/Users\/crack_david\/Sites\/nkn-api\/app\/Http\/Controllers\/BlockController.php",
    "line": 125,
    "trace": [
        {
            "function": "showBlockTransactions",
            "class": "App\\Http\\Controllers\\BlockController",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Controller.php",
            "line": 54,
            "function": "call_user_func_array"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/ControllerDispatcher.php",
            "line": 45,
            "function": "callAction",
            "class": "Illuminate\\Routing\\Controller",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php",
            "line": 212,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\ControllerDispatcher",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php",
            "line": 169,
            "function": "runController",
            "class": "Illuminate\\Routing\\Route",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 679,
            "function": "run",
            "class": "Illuminate\\Routing\\Route",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Middleware\/SubstituteBindings.php",
            "line": 41,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\SubstituteBindings",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Middleware\/ThrottleRequests.php",
            "line": 58,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Routing\\Middleware\\ThrottleRequests",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 681,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 656,
            "function": "runRouteWithinStack",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 622,
            "function": "runRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php",
            "line": 611,
            "function": "dispatchToRoute",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 176,
            "function": "dispatch",
            "class": "Illuminate\\Routing\\Router",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 30,
            "function": "Illuminate\\Foundation\\Http\\{closure}",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/barryvdh\/laravel-cors\/src\/HandleCors.php",
            "line": 36,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Barryvdh\\Cors\\HandleCors",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/fideloper\/proxy\/src\/TrustProxies.php",
            "line": 57,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Fideloper\\Proxy\\TrustProxies",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/TransformsRequest.php",
            "line": 31,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/TransformsRequest.php",
            "line": 31,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/ValidatePostSize.php",
            "line": 27,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Middleware\/CheckForMaintenanceMode.php",
            "line": 62,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 151,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php",
            "line": 53,
            "function": "Illuminate\\Pipeline\\{closure}",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php",
            "line": 104,
            "function": "Illuminate\\Routing\\{closure}",
            "class": "Illuminate\\Routing\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 151,
            "function": "then",
            "class": "Illuminate\\Pipeline\\Pipeline",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php",
            "line": 116,
            "function": "sendRequestThroughRouter",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Tools\/ResponseStrategies\/ResponseCallStrategy.php",
            "line": 199,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Http\\Kernel",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Tools\/ResponseStrategies\/ResponseCallStrategy.php",
            "line": 185,
            "function": "callLaravelRoute",
            "class": "Mpociot\\ApiDoc\\Tools\\ResponseStrategies\\ResponseCallStrategy",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Tools\/ResponseStrategies\/ResponseCallStrategy.php",
            "line": 25,
            "function": "makeApiCall",
            "class": "Mpociot\\ApiDoc\\Tools\\ResponseStrategies\\ResponseCallStrategy",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Tools\/ResponseResolver.php",
            "line": 33,
            "function": "__invoke",
            "class": "Mpociot\\ApiDoc\\Tools\\ResponseStrategies\\ResponseCallStrategy",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Tools\/ResponseResolver.php",
            "line": 42,
            "function": "resolve",
            "class": "Mpociot\\ApiDoc\\Tools\\ResponseResolver",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Tools\/Generator.php",
            "line": 54,
            "function": "getResponse",
            "class": "Mpociot\\ApiDoc\\Tools\\ResponseResolver",
            "type": "::"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Commands\/GenerateDocumentation.php",
            "line": 194,
            "function": "processRoute",
            "class": "Mpociot\\ApiDoc\\Tools\\Generator",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Commands\/GenerateDocumentation.php",
            "line": 56,
            "function": "processRoutes",
            "class": "Mpociot\\ApiDoc\\Commands\\GenerateDocumentation",
            "type": "->"
        },
        {
            "function": "handle",
            "class": "Mpociot\\ApiDoc\\Commands\\GenerateDocumentation",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 29,
            "function": "call_user_func_array"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 87,
            "function": "Illuminate\\Container\\{closure}",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php",
            "line": 31,
            "function": "callBoundMethod",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php",
            "line": 564,
            "function": "call",
            "class": "Illuminate\\Container\\BoundMethod",
            "type": "::"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php",
            "line": 183,
            "function": "call",
            "class": "Illuminate\\Container\\Container",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/symfony\/console\/Command\/Command.php",
            "line": 251,
            "function": "execute",
            "class": "Illuminate\\Console\\Command",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php",
            "line": 170,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Command\\Command",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/symfony\/console\/Application.php",
            "line": 886,
            "function": "run",
            "class": "Illuminate\\Console\\Command",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/symfony\/console\/Application.php",
            "line": 262,
            "function": "doRunCommand",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/symfony\/console\/Application.php",
            "line": 145,
            "function": "doRun",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Application.php",
            "line": 89,
            "function": "run",
            "class": "Symfony\\Component\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Console\/Kernel.php",
            "line": 122,
            "function": "run",
            "class": "Illuminate\\Console\\Application",
            "type": "->"
        },
        {
            "file": "\/Users\/crack_david\/Sites\/nkn-api\/artisan",
            "line": 37,
            "function": "handle",
            "class": "Illuminate\\Foundation\\Console\\Kernel",
            "type": "->"
        }
    ]
}
```

### HTTP Request
`GET blocks/{block_id}/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    block_id |  required  | Limits the results returned
    withoutpayload |  optional  | add transaction payload to result

<!-- END_f0d1da991c4ef405b9f00f2ce8d8fb72 -->

#Payloads

Endpoints for querying transaction-payloads
<!-- START_e96045a388d862924708781e4b0d57c3 -->
## Get single payload

Returns a specific payload based on a transaction-database-id

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/payloads/{tId}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/payloads/{tId}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{}
```

### HTTP Request
`GET payloads/{tId}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    tId |  required  | The database-id of the according transaction

<!-- END_e96045a388d862924708781e4b0d57c3 -->

#Statistics

Endpoints for NKN Network statistics
<!-- START_694c2a0d01d3368bad83110c4aae336a -->
## Daily blocks

Shows the amount of blocks generated per day

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/daily/blocks" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/daily/blocks",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/daily/blocks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    pubkey |  optional  | Only return blocks that were signed by the given public key

<!-- END_694c2a0d01d3368bad83110c4aae336a -->

<!-- START_f3f77e8838daa69caff607ed39b94630 -->
## Daily transactions

Shows the amount of transactions generated per day

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/daily/transactions" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/daily/transactions",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/daily/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_f3f77e8838daa69caff607ed39b94630 -->

<!-- START_c7bedc389e540c78139d82fdacf1b28e -->
## Daily transfers

Shows the amount of transfers generated per day

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/daily/transfers" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/daily/transfers",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/daily/transfers`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_c7bedc389e540c78139d82fdacf1b28e -->

<!-- START_d7d866d1f9fe5d0652ab87e135a131c4 -->
## Monthly blocks

Shows the amount of blocks generated per month

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/monthly/blocks" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/monthly/blocks",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/monthly/blocks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    pubkey |  optional  | Only return blocks that were signed by the given public key

<!-- END_d7d866d1f9fe5d0652ab87e135a131c4 -->

<!-- START_f6e27cc93a66a5f21c123db60eccb148 -->
## Monthly transactions

Shows the amount of transactions generated per day

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/monthly/transactions" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/monthly/transactions",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/monthly/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_f6e27cc93a66a5f21c123db60eccb148 -->

<!-- START_fb8959b54ae53d91ae432ced6ea53f86 -->
## Monthly transfers

Shows the amount of transfers generated per day

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/monthly/transfers" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/monthly/transfers",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/monthly/transfers`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_fb8959b54ae53d91ae432ced6ea53f86 -->

<!-- START_914079fd8300e63d58677bc93344f2ce -->
## All miners

Shows the amount of mined blocks by all wallet addresses

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/statistics/miners" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/statistics/miners",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[]
```

### HTTP Request
`GET statistics/miners`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_914079fd8300e63d58677bc93344f2ce -->

#Transactions

Endpoints for querying transactions
<!-- START_ac55eceb73f5d636ddc0fec5e8427a6c -->
## Get single transaction by hash

Returns a specific block based on the height or block hash

> Example request:

```bash
curl -X GET -G "https://api.nknx.org/transactions/{tHash}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/transactions/{tHash}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{}
```

### HTTP Request
`GET transactions/{tHash}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    block_id |  required  | Id of the resource
    withoutPayload |  optional  | remove payload
    withoutOutputs |  optional  | remove outputs
    withoutInputs |  optional  | remove inputs
    withoutAttributes |  optional  | remove attributes

<!-- END_ac55eceb73f5d636ddc0fec5e8427a6c -->

#User Nodes

Endpoints for querying user nodes
<!-- START_b0e01a6b677164d45a77e27f71b41824 -->
## Get single node by id
Returns a specific user-node based on the id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/nodes/{node}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/nodes/{node}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "id": 172,
    "label": "nknx",
    "state": 0,
    "syncState": "PersistFinished",
    "port": 0,
    "nodePort": 0,
    "chordPort": null,
    "jsonPort": 30003,
    "wsPort": 30002,
    "addr": "159.65.200.254",
    "time": 1541687107532520392,
    "version": 0,
    "services": null,
    "relay": 0,
    "height": 0,
    "txnCnt": 1,
    "rxTxnCnt": 8510,
    "chordID": "3eda464223dbae834131ac7ea964f7a273933bfaf8168786f5efead910015815",
    "softwareVersion": "0.5.0-alpha-7-g2613",
    "latestBlockHeight": 207011,
    "online": 1,
    "user_id": 2,
    "created_at": "2018-11-04 15:51:49",
    "updated_at": "2018-11-17 12:46:08"
}
```

### HTTP Request
`GET nodes/{node}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    node |  required  | Id of the resource

<!-- END_b0e01a6b677164d45a77e27f71b41824 -->

<!-- START_92c5644d16ba1659af543a1798023e95 -->
## Remove single node by id
Remove the specified user-node from the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://api.nknx.org/nodes/{node}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/nodes/{node}",
    "method": "DELETE",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{}
```

### HTTP Request
`DELETE nodes/{node}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    node |  required  | Id of the resource

<!-- END_92c5644d16ba1659af543a1798023e95 -->

#User Wallets

Endpoints for querying stored user wallets
<!-- START_5af44083f99140f11aa1d171699ccba0 -->
## Get single wallet by walletAddress
Returns a specific user-wallet based on the address

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/walletAddresses/{walletAddress}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/walletAddresses/{walletAddress}",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "id": 2,
    "label": "nknx",
    "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
    "balance": 2341,
    "user_id": 1,
    "created_at": "2018-11-17 09:42:58",
    "updated_at": "2018-11-17 09:42:58"
}
```

### HTTP Request
`GET walletAddresses/{walletAddress}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  optional  | a stored nkn wallet address

<!-- END_5af44083f99140f11aa1d171699ccba0 -->

<!-- START_515560a23f83c071788e5cd0eb7f0639 -->
## Remove single wallet by id
Remove the specified user-wallet from the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://api.nknx.org/walletAddresses/{walletAddress}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/walletAddresses/{walletAddress}",
    "method": "DELETE",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{}
```

### HTTP Request
`DELETE walletAddresses/{walletAddress}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  required  | Id of the resource

<!-- END_515560a23f83c071788e5cd0eb7f0639 -->

<!-- START_0b5586a8fafa36ae718b2cfc3ec7e519 -->
## Get mining output per day
Get the daily mining output based on a database-wallet id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/walletAddresses/{walletAddress}/miningOutputDaily" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/walletAddresses/{walletAddress}/miningOutputDaily",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[
    {
        "count": 2172,
        "date": "2018-11-17"
    },
    {
        "count": 4145,
        "date": "2018-11-16"
    },
    {
        "count": 4129,
        "date": "2018-11-15"
    },
    {
        "count": 4075,
        "date": "2018-11-14"
    },
    {
        "count": 4124,
        "date": "2018-11-13"
    },
    {
        "count": 3956,
        "date": "2018-11-12"
    },
    {
        "count": 3302,
        "date": "2018-11-11"
    },
    {
        "count": 2363,
        "date": "2018-11-10"
    }
]
```

### HTTP Request
`GET walletAddresses/{walletAddress}/miningOutputDaily`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  required  | Id of the resource
    latest |  optional  | Limits the days returned

<!-- END_0b5586a8fafa36ae718b2cfc3ec7e519 -->

<!-- START_8d793b6e78f10ba46dcc16b302e1edc1 -->
## Get mining output per month
Get the monthly mining output based on a database-wallet id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://api.nknx.org/walletAddresses/{walletAddress}/miningOutputMonthly" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.nknx.org/walletAddresses/{walletAddress}/miningOutputMonthly",
    "method": "GET",
    "headers": {
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
[
    {
        "count": 2172,
        "month": "8"
    },
    {
        "count": 4145,
        "month": "7"
    },
    {
        "count": 4129,
        "month": "6"
    },
    {
        "count": 4075,
        "month": "5"
    },
    {
        "count": 4124,
        "month": "4"
    },
    {
        "count": 3956,
        "month": "3"
    },
    {
        "count": 3302,
        "month": "2"
    },
    {
        "count": 2363,
        "month": "1"
    }
]
```

### HTTP Request
`GET walletAddresses/{walletAddress}/miningOutputMonthly`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  required  | Id of the resource
    latest |  optional  | Limits the months returned

<!-- END_8d793b6e78f10ba46dcc16b302e1edc1 -->


