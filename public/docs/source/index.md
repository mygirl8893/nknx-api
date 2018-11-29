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
[Get Postman Collection](http://nknx.org/docs/collection.json)

<!-- END_INFO -->

#Address book

Endpoints for querying registered names in the NKN blockchain
<!-- START_46047d67d0a6007be0e412f3d2c69f99 -->
## Get all registered wallet names

Returns all registered wallet names and the registrants public key

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/walletNames" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/walletNames",
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
        "name": "microsoft",
        "public_key": "02ac9d242fad2ac6e231c4ecda82b1b108c12b1e21a685053a41d64f9886f6fdea",
        "address": "NgugCfHpsYV2zMDwHwEqm147VsW1akrSyL"
    },
    {
        "name": "elonmusk",
        "public_key": "035e5cce1fa0049684b6e1438d3398382dcca298f4e0363109a90a3d7039009bc7",
        "address": "Nd4DfvaB95YAQzVAPtFc59Ap8YpTq9urBo"
    },
    {
        "name": "trueinsider",
        "public_key": "039bd29d184aa8acc963a46c083129737c0a78615b86656463b3f1e5e06df9a1a3",
        "address": "NTs3B7YsM6aRDCSMzqTdgbdkW5Q6YBBUZn"
    },
    {
        "name": "bittorrent",
        "public_key": "027865c7c961e7d5d3f96920803f4d6a0e4e48cca4f98fd23b370799c2598053b5",
        "address": "NQ7KwEzta2CJz2MCFzq2xf8jyH8UUE7PpU"
    }
]
```

### HTTP Request
`GET api/walletNames`


<!-- END_46047d67d0a6007be0e412f3d2c69f99 -->

#Addresses

Endpoints for address-based queries
<!-- START_f62d434079dff3acd53aa774d160d2ad -->
## Get all addresses

Returns all addresses in simple pagination format starting with the last active one

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/addresses" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/addresses",
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
    "current_page": 1,
    "data": [
        {
            "address": "NMbGMaugVANhQBtgQnQAa7GAUTKVcapFUJ",
            "last_transaction": "2018-11-17 13:46:08",
            "transactions": 4423,
            "first_transaction": "2018-10-23 09:00:04"
        },
        {
            "address": "NWZYrmYd6J7ZyckFEjeRKXmXwEQ8K6Chm1",
            "last_transaction": "2018-11-17 13:46:07",
            "transactions": 956,
            "first_transaction": "2018-11-05 20:40:08"
        },
        {
            "address": "NgUbhzZxNWcCVbU8XJHyGtrwsmWixTDA6y",
            "last_transaction": "2018-11-17 13:46:06",
            "transactions": 2,
            "first_transaction": "2018-11-17 10:02:06"
        },
        {
            "address": "NQogc7UGuvF7VwzdWhjmtqf711azdmYLSY",
            "last_transaction": "2018-11-17 13:45:07",
            "transactions": 6361,
            "first_transaction": "2018-10-08 23:20:54"
        },
        {
            "address": "NSwSRrZvz1c75DhiDn6tA9AhondTP5x12U",
            "last_transaction": "2018-11-17 13:45:07",
            "transactions": 3306,
            "first_transaction": "2018-10-08 17:53:35"
        }
    ],
    "first_page_url": "http:\/\/localhost\/api\/addresses?page=1",
    "from": 1,
    "next_page_url": "http:\/\/localhost\/api\/addresses?page=2",
    "path": "http:\/\/localhost\/api\/addresses",
    "per_page": "5",
    "prev_page_url": null,
    "to": 5
}
```

### HTTP Request
`GET api/addresses`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    per_page |  optional  | Number of results per page

<!-- END_f62d434079dff3acd53aa774d160d2ad -->

<!-- START_25f4303d28e06d127578df97937cdb67 -->
## Get single address

Returns a specific address with first/last transaction date and transaction count

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/addresses/{address}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/addresses/{address}",
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
    "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6sGBQW",
    "last_transaction": "2018-11-15 15:43:06",
    "transactions": 2162,
    "first_transaction": "2018-10-08 11:55:28"
}
```

### HTTP Request
`GET api/addresses/{address}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    address |  required  | A NKN wallet address

<!-- END_25f4303d28e06d127578df97937cdb67 -->

#Auth and User management

APIs for managing users
<!-- START_2e1c96dcffcfe7e0eb58d6408f1d619e -->
## Register a user

Creates an initial user entity in the database and also starts verification process

> Example request:

```bash
curl -X POST "https://nknx.org/api/auth/register"     -d "email"="7CljQY3qF4g7Ov0f" \
    -d "name"="Yu5zIU60pjG4eiUn" \
    -d "password"="QiEHff3Nr5nGSrJu" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/register",
    "method": "POST",
    "data": {
        "email": "7CljQY3qF4g7Ov0f",
        "name": "Yu5zIU60pjG4eiUn",
        "password": "QiEHff3Nr5nGSrJu"
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
`POST api/auth/register`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address
    name | string |  required  | Username
    password | string |  required  | User password

<!-- END_2e1c96dcffcfe7e0eb58d6408f1d619e -->

<!-- START_6324d9f8e3fb064584f93f3ebdf66b38 -->
## Verify email

Accepts a token provided in an eMail link and verifies the user entity

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/auth/verify/{token}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/verify/{token}",
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
`GET api/auth/verify/{token}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    token |  required  | The verification token

<!-- END_6324d9f8e3fb064584f93f3ebdf66b38 -->

<!-- START_ee16239342f010f9171e0de62c27cc31 -->
## Set new password

Sets a new password for a user from a provided token

> Example request:

```bash
curl -X POST "https://nknx.org/api/auth/reset/{token}"     -d "password"="mGHdLlBgTC8gSg2Q" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/reset/{token}",
    "method": "POST",
    "data": {
        "password": "mGHdLlBgTC8gSg2Q"
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
`POST api/auth/reset/{token}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    password | string |  required  | User password

<!-- END_ee16239342f010f9171e0de62c27cc31 -->

<!-- START_4a5704f106a2ca07c93f86307b6e63e6 -->
## Reset password

Creates a password reset mail and an entry in the database

> Example request:

```bash
curl -X POST "https://nknx.org/api/auth/reset"     -d "email"="fD9PhnE3u3rpikpu" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/reset",
    "method": "POST",
    "data": {
        "email": "fD9PhnE3u3rpikpu"
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
`POST api/auth/reset`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address

<!-- END_4a5704f106a2ca07c93f86307b6e63e6 -->

<!-- START_a925a8d22b3615f12fca79456d286859 -->
## Login

Logs a user in

> Example request:

```bash
curl -X POST "https://nknx.org/api/auth/login"     -d "email"="KGh8DT5llxiiws3g" \
    -d "password"="WWKQyq0tHEGiwf8S" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/login",
    "method": "POST",
    "data": {
        "email": "KGh8DT5llxiiws3g",
        "password": "WWKQyq0tHEGiwf8S"
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
`POST api/auth/login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User email address
    password | string |  required  | User password

<!-- END_a925a8d22b3615f12fca79456d286859 -->

<!-- START_ff6d656b6d81a61adda963b8702034d2 -->
## User
Returns the current logged in User

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/auth/user" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/user",
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
`GET api/auth/user`


<!-- END_ff6d656b6d81a61adda963b8702034d2 -->

<!-- START_19ff1b6f8ce19d3c444e9b518e8f7160 -->
## Logout
Logs a user out

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://nknx.org/api/auth/logout" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/logout",
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
`POST api/auth/logout`


<!-- END_19ff1b6f8ce19d3c444e9b518e8f7160 -->

<!-- START_f49d1fe07ea530d7423a6a68575da349 -->
## Resend verification mail
Recreates VerifyUser entity and resends the verification mail

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/auth/resendVerification" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/resendVerification",
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
`GET api/auth/resendVerification`


<!-- END_f49d1fe07ea530d7423a6a68575da349 -->

<!-- START_46c2a6d1497a2724f8515eff6024367e -->
## Refresh
Refreshes the current users session

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/auth/refresh" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/auth/refresh",
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
`GET api/auth/refresh`


<!-- END_46c2a6d1497a2724f8515eff6024367e -->

#Blocks

Endpoints for block-based queries
<!-- START_480250b6d6418210dc1f3d2fbdde9a6f -->
## Get all blocks

Returns all blocks with corresponding headers in simple pagination format starting with the latest one

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/blocks" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/blocks",
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
    "current_page": 1,
    "data": [
        {
            "id": 240759,
            "hash": "7f702365d8e8f7b82737c1fe292913a9f8e3587af6ebf3ca8a6df1a8136477bf",
            "transaction_count": 3,
            "created_at": "2018-11-17 13:46:06",
            "updated_at": "2018-11-17 13:46:06",
            "header": {
                "id": 240759,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 240759,
                "prevBlockHash": "0a325a5c0e94cb77529ff80045c44209714f03663f3c58d78e0a81780720a137",
                "signature": "60c5506893650eea0857084d91714bcc6d915804da60ccda253c47bdf673d4b50f11d248d35c5b957458092b28376eb67730a91d92635b23b22bcfdfca07eb92",
                "signer": "03f47650d4462703355478e4613f8247447d3cef10cc6be1f7e781f0443e94eb87",
                "timestamp": "2018-11-17 13:45:04",
                "transactionsRoot": "a7ea3cfd0f58c564bc166334b8199c920caf30a5e87592588494926e47ab26f5",
                "version": 1,
                "winningHash": "54969bd3295ed29338ff4ae87a06e1d49bda4de09c5106bba4492d1bbf9c4a92",
                "winningHashType": 1,
                "block_id": 240759,
                "created_at": "2018-11-17 13:46:06",
                "updated_at": "2018-11-17 13:46:06"
            }
        },
        {
            "id": 240758,
            "hash": "0a325a5c0e94cb77529ff80045c44209714f03663f3c58d78e0a81780720a137",
            "transaction_count": 6,
            "created_at": "2018-11-17 13:45:07",
            "updated_at": "2018-11-17 13:45:07",
            "header": {
                "id": 240758,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 240758,
                "prevBlockHash": "4e523b1071b070b9f7e2d0ef0e5e681a44f6ee6d21c5cb94b6e5cb57909ebd9f",
                "signature": "e4d17c56827ffd88095fa699dd2e2754d712b15bd3bf232b1928a487495f2dce895f917f1a0ba45c8949ea8e29d19a864d2d922a5572822eccf64cb30c656286",
                "signer": "02dc69b3af8f97b47e078fc82d4835621cb0be1b6d0ea98c69b6e9a17b99e768cd",
                "timestamp": "2018-11-17 13:44:44",
                "transactionsRoot": "81c5a667d0bec0a2e36a9a7ff5a2ddf70e29ba069e3bbefcf91a67c78c1dae61",
                "version": 1,
                "winningHash": "b4b016a64a7c9e0b35a0eab3ed18899da62afeb10e39b8a8e8c27cd52ae6ed67",
                "winningHashType": 1,
                "block_id": 240758,
                "created_at": "2018-11-17 13:45:07",
                "updated_at": "2018-11-17 13:45:07"
            }
        },
        {
            "id": 240757,
            "hash": "4e523b1071b070b9f7e2d0ef0e5e681a44f6ee6d21c5cb94b6e5cb57909ebd9f",
            "transaction_count": 6,
            "created_at": "2018-11-17 13:45:07",
            "updated_at": "2018-11-17 13:45:07",
            "header": {
                "id": 240757,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 240757,
                "prevBlockHash": "61dc7dabf8c698b2186d26930b9626a78ef129ee9f7c375874c71c5edaba1abb",
                "signature": "679c3e65c945b8ac2e31d206efdaee8248e0e08169b1a432e8f208b7fede08c2265f35b5b52cea2a7b13f13e816f01ae88613807c424abf0465a10e89f96a53a",
                "signer": "0391ec6b95b47c906d7e73a9180f6df66e54a322f13b96521043d426ed9dd7eaf9",
                "timestamp": "2018-11-17 13:44:24",
                "transactionsRoot": "5429f1aad52bcf648e20670905d8992a30235a1ffe4aae66fadd0ebfa5cc0362",
                "version": 1,
                "winningHash": "45c2a1c19c2bfa22dec9b2f7d03bd49ca11b718db7b0ed23ccb7345288e3852f",
                "winningHashType": 1,
                "block_id": 240757,
                "created_at": "2018-11-17 13:45:07",
                "updated_at": "2018-11-17 13:45:07"
            }
        },
        {
            "id": 240756,
            "hash": "61dc7dabf8c698b2186d26930b9626a78ef129ee9f7c375874c71c5edaba1abb",
            "transaction_count": 4,
            "created_at": "2018-11-17 13:45:06",
            "updated_at": "2018-11-17 13:45:06",
            "header": {
                "id": 240756,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 240756,
                "prevBlockHash": "3934e5bd1a2ea02d7ca3c109e72815f58b0b819c0e910bc49230b2a276ed3682",
                "signature": "1f7641a77b2074c65aec83d151a56f465e1db32a246e532af713bd95542925daf38608e7d77f5e66f0ca89d2e5b93b3bed4c6aa51aeab4d9a07e6b21670c2102",
                "signer": "033e101011325621726f2bbc2e9e8ba417be14627469e279b304f676dd2decec97",
                "timestamp": "2018-11-17 13:44:03",
                "transactionsRoot": "3610e79252d0d7a9bdda397edb1f4982561895fcc96c8d18480a80b9510c6ab7",
                "version": 1,
                "winningHash": "189209c974aff74380595b8b9a0b2e0d0c9a3cff8434af307116131526f4eab6",
                "winningHashType": 1,
                "block_id": 240756,
                "created_at": "2018-11-17 13:45:06",
                "updated_at": "2018-11-17 13:45:06"
            }
        }
    ],
    "first_page_url": "http:\/\/localhost\/api\/blocks?page=1",
    "from": 1,
    "next_page_url": "http:\/\/localhost\/api\/blocks?page=2",
    "path": "http:\/\/localhost\/api\/blocks",
    "per_page": "4",
    "prev_page_url": null,
    "to": 4
}
```

### HTTP Request
`GET api/blocks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    per_page |  optional  | Number of results per page
    from |  optional  | Starting date in the format "YEAR-MONTH-DAY HOUR:MINUTE:SECOND"
    to |  optional  | End date in the format "YEAR-MONTH-DAY HOUR:MINUTE:SECOND"
    blockproposer |  optional  | Returns only blocks which are singed by the given public key

<!-- END_480250b6d6418210dc1f3d2fbdde9a6f -->

<!-- START_0699455da37c6eafaf80e51235f5dc06 -->
## Get single block by height/hash

Returns a specific block based on the height or block hash

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/blocks/{block_id}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/blocks/{block_id}",
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
    "id": 212345,
    "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
    "transaction_count": 5,
    "created_at": "2018-11-10 00:10:07",
    "updated_at": "2018-11-10 00:10:07",
    "transactions": [
        {
            "id": 1068263,
            "hash": "0f4d16d2483371bab1e1a4e9a8f489058707e53c542679f33988249f8824cb30",
            "payloadVersion": 0,
            "txType": 0,
            "block_id": 212345,
            "sender": null,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07"
        },
        {
            "id": 1068264,
            "hash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
            "payloadVersion": 0,
            "txType": 66,
            "block_id": 212345,
            "sender": null,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07"
        },
        {
            "id": 1068265,
            "hash": "ded2a288cb356279814b8c985433bac67b10e0b9d345f6550a6e4d0fcc84c167",
            "payloadVersion": 0,
            "txType": 66,
            "block_id": 212345,
            "sender": null,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07"
        },
        {
            "id": 1068266,
            "hash": "29c8ef8a5b9aa329f956a46e69e79b1375a16b4db8b88fe4a7b70475d7d72546",
            "payloadVersion": 0,
            "txType": 66,
            "block_id": 212345,
            "sender": null,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07"
        },
        {
            "id": 1068267,
            "hash": "bc7f791822d59cd022eddcdd2e0e253c400e50fcd9f9534b2ccf5506ad2b1f08",
            "payloadVersion": 0,
            "txType": 66,
            "block_id": 212345,
            "sender": null,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07"
        }
    ],
    "header": {
        "id": 212345,
        "chordId": "",
        "hash": "0000000000000000000000000000000000000000000000000000000000000000",
        "height": 212345,
        "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
        "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
        "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
        "timestamp": "2018-11-10 00:09:23",
        "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
        "version": 1,
        "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
        "winningHashType": 1,
        "block_id": 212345,
        "created_at": "2018-11-10 00:10:07",
        "updated_at": "2018-11-10 00:10:07",
        "nextBlockHash": "bb6f4772d206418f5e98e3b2f973dc0179f0014bf284031aacd49f5e1d8be257"
    }
}
```

### HTTP Request
`GET api/blocks/{block_id}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    block_id |  required  | Id of the resource
    withoutTransactions |  optional  | add block transactions to result

<!-- END_0699455da37c6eafaf80e51235f5dc06 -->

<!-- START_685b5bb79cd87d4a0777878aaa05980f -->
## Get transactions

Returns all transactions of a specific block based on the height or block hash

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/blocks/{block_id}/transactions" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/blocks/{block_id}/transactions",
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
        "id": 1068263,
        "hash": "0f4d16d2483371bab1e1a4e9a8f489058707e53c542679f33988249f8824cb30",
        "payloadVersion": 0,
        "txType": 0,
        "block_id": 212345,
        "sender": null,
        "created_at": "2018-11-10 00:10:07",
        "updated_at": "2018-11-10 00:10:07",
        "outputs": [
            {
                "id": 212585,
                "address": "NYBEDwseGEmQncr47tu2G7ZjJjTbmcb7Bw",
                "assetID": "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02",
                "value": "15",
                "transaction_id": 1068263,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        ],
        "attributes": [
            {
                "id": 1068263,
                "data": "326822fd59dfc6a28c6caa44f43db03cb5218aa534cd2d605ef77846101c0e12",
                "usage": 0,
                "transaction_id": 1068263,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        ],
        "block": {
            "id": 212345,
            "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
            "transaction_count": 5,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07",
            "header": {
                "id": 212345,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 212345,
                "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
                "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
                "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
                "timestamp": "2018-11-10 00:09:23",
                "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
                "version": 1,
                "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
                "winningHashType": 1,
                "block_id": 212345,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        }
    },
    {
        "id": 1068264,
        "hash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
        "payloadVersion": 0,
        "txType": 66,
        "block_id": 212345,
        "sender": null,
        "created_at": "2018-11-10 00:10:07",
        "updated_at": "2018-11-10 00:10:07",
        "outputs": [],
        "attributes": [
            {
                "id": 1068264,
                "data": "595f574409718ff9784e7d4dc045953dfc3b5c83bb81e59e3e278e6b9c98d967",
                "usage": 0,
                "transaction_id": 1068264,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        ],
        "block": {
            "id": 212345,
            "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
            "transaction_count": 5,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07",
            "header": {
                "id": 212345,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 212345,
                "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
                "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
                "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
                "timestamp": "2018-11-10 00:09:23",
                "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
                "version": 1,
                "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
                "winningHashType": 1,
                "block_id": 212345,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        }
    },
    {
        "id": 1068265,
        "hash": "ded2a288cb356279814b8c985433bac67b10e0b9d345f6550a6e4d0fcc84c167",
        "payloadVersion": 0,
        "txType": 66,
        "block_id": 212345,
        "sender": null,
        "created_at": "2018-11-10 00:10:07",
        "updated_at": "2018-11-10 00:10:07",
        "outputs": [],
        "attributes": [
            {
                "id": 1068265,
                "data": "3f125bb991419a4e996050599ea28883b952bda8f8bdf97f7303206f051856ab",
                "usage": 0,
                "transaction_id": 1068265,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        ],
        "block": {
            "id": 212345,
            "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
            "transaction_count": 5,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07",
            "header": {
                "id": 212345,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 212345,
                "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
                "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
                "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
                "timestamp": "2018-11-10 00:09:23",
                "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
                "version": 1,
                "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
                "winningHashType": 1,
                "block_id": 212345,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        }
    },
    {
        "id": 1068266,
        "hash": "29c8ef8a5b9aa329f956a46e69e79b1375a16b4db8b88fe4a7b70475d7d72546",
        "payloadVersion": 0,
        "txType": 66,
        "block_id": 212345,
        "sender": null,
        "created_at": "2018-11-10 00:10:07",
        "updated_at": "2018-11-10 00:10:07",
        "outputs": [],
        "attributes": [
            {
                "id": 1068266,
                "data": "6c7cae23c3244bfc5622c84da9658741126794580d1bdd660163d520555705f5",
                "usage": 0,
                "transaction_id": 1068266,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        ],
        "block": {
            "id": 212345,
            "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
            "transaction_count": 5,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07",
            "header": {
                "id": 212345,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 212345,
                "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
                "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
                "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
                "timestamp": "2018-11-10 00:09:23",
                "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
                "version": 1,
                "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
                "winningHashType": 1,
                "block_id": 212345,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        }
    },
    {
        "id": 1068267,
        "hash": "bc7f791822d59cd022eddcdd2e0e253c400e50fcd9f9534b2ccf5506ad2b1f08",
        "payloadVersion": 0,
        "txType": 66,
        "block_id": 212345,
        "sender": null,
        "created_at": "2018-11-10 00:10:07",
        "updated_at": "2018-11-10 00:10:07",
        "outputs": [],
        "attributes": [
            {
                "id": 1068267,
                "data": "9324116807873332fcb0c9525ecb37357f5b6e896fffea0165fc6d80f69982bb",
                "usage": 0,
                "transaction_id": 1068267,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        ],
        "block": {
            "id": 212345,
            "hash": "4a78e2f807a02d54cdc271becf8f62013302ce823dd70531de5382bdbb997982",
            "transaction_count": 5,
            "created_at": "2018-11-10 00:10:07",
            "updated_at": "2018-11-10 00:10:07",
            "header": {
                "id": 212345,
                "chordId": "",
                "hash": "0000000000000000000000000000000000000000000000000000000000000000",
                "height": 212345,
                "prevBlockHash": "da5ac4f1802256fef7c21b8d8907fb950e707849a4f74e0710af0d43cb3f7687",
                "signature": "dd4d1cde07245a89e05d0196b4762931c4ad1bcc99a7fb2d543a0832e994d7cd8f7f12d6d7d54b7c4e5553276fbb17fbb422a2e1fcb7ebcd44ecebfb57121d86",
                "signer": "03ec987bb6698154b769f0e37ef1e7ef75524a5dc83cffb08a88c2c1cc7a38d64f",
                "timestamp": "2018-11-10 00:09:23",
                "transactionsRoot": "8f30383e38d8f868d8c587d82cb715ce38eb65fc6e169e27223468ceea3a62c2",
                "version": 1,
                "winningHash": "ae537ee400a7c7866bba6b562f864685fdcdf93378cd73d0d5be749de9330d97",
                "winningHashType": 1,
                "block_id": 212345,
                "created_at": "2018-11-10 00:10:07",
                "updated_at": "2018-11-10 00:10:07"
            }
        }
    }
]
```

### HTTP Request
`GET api/blocks/{block_id}/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    block_id |  required  | Limits the results returned
    withoutpayload |  optional  | add transaction payload to result

<!-- END_685b5bb79cd87d4a0777878aaa05980f -->

#Node crawler

Endpoints for querying crawled Nodes
<!-- START_49cd414c82b7c000ffe2bbd23daeda0f -->
## Get all nodes

Returns a list of all node-ips crawled by the API

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/crawledNodes" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/crawledNodes",
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
        "id": 3,
        "ip": "35.187.201.101",
        "created_at": "2018-11-01 08:29:37",
        "updated_at": "2018-11-01 08:29:39",
        "continent_code": "NA",
        "continent_name": "North America",
        "country_code2": "US",
        "country_code3": "USA",
        "country_name": "United States",
        "country_capital": "Washington",
        "state_prov": "Virginia",
        "district": "Loudoun",
        "city": "Ashburn",
        "zipcode": "20149",
        "latitude": "39.04380000",
        "longitude": "-77.48740000",
        "is_eu": "0",
        "calling_code": "+1",
        "country_tld": ".us",
        "isp": "Google LLC",
        "connection_type": "",
        "organization": "Google LLC",
        "geoname_id": "4744870"
    },
    {
        "id": 4,
        "ip": "35.198.198.253",
        "created_at": "2018-11-01 08:29:37",
        "updated_at": "2018-11-01 08:29:39",
        "continent_code": "AS",
        "continent_name": "Asia",
        "country_code2": "SG",
        "country_code3": "SGP",
        "country_name": "Singapore",
        "country_capital": "Singapore",
        "state_prov": "Central Singapore",
        "district": "Queenstown Estate",
        "city": "Singapore",
        "zipcode": "",
        "latitude": "1.27623000",
        "longitude": "103.80000000",
        "is_eu": "0",
        "calling_code": "+65",
        "country_tld": ".sg",
        "isp": "Google LLC",
        "connection_type": "",
        "organization": "Google LLC",
        "geoname_id": "1884386"
    },
    {
        "id": 7,
        "ip": "35.204.197.53",
        "created_at": "2018-11-01 08:29:37",
        "updated_at": "2018-11-01 08:29:39",
        "continent_code": "NA",
        "continent_name": "North America",
        "country_code2": "US",
        "country_code3": "USA",
        "country_name": "United States",
        "country_capital": "Washington",
        "state_prov": "Virginia",
        "district": "Loudoun",
        "city": "Ashburn",
        "zipcode": "20149",
        "latitude": "39.04380000",
        "longitude": "-77.48740000",
        "is_eu": "0",
        "calling_code": "+1",
        "country_tld": ".us",
        "isp": "Google LLC",
        "connection_type": "",
        "organization": "Google LLC",
        "geoname_id": "4744870"
    }
]
```

### HTTP Request
`GET api/crawledNodes`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    withLocation |  optional  | Add location data to the result

<!-- END_49cd414c82b7c000ffe2bbd23daeda0f -->

#Outputs

Endpoints for querying transaction-outputs
<!-- START_8835d32f6c07647414279af547b0294f -->
## Get all outputs

Returns all outputs in simple pagination format starting with the latest one

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/outputs" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/outputs",
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
    "current_page": 1,
    "data": [
        {
            "id": 241079,
            "address": "NMbGMaugVANhQBtgQnQAa7GAUTKVcapFUJ",
            "assetID": "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02",
            "value": "15",
            "transaction_id": 1190096,
            "created_at": "2018-11-17 13:46:08",
            "updated_at": "2018-11-17 13:46:08"
        },
        {
            "id": 241078,
            "address": "NWZYrmYd6J7ZyckFEjeRKXmXwEQ8K6Chm1",
            "assetID": "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02",
            "value": "15",
            "transaction_id": 1190090,
            "created_at": "2018-11-17 13:46:07",
            "updated_at": "2018-11-17 13:46:07"
        },
        {
            "id": 241077,
            "address": "NgUbhzZxNWcCVbU8XJHyGtrwsmWixTDA6y",
            "assetID": "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02",
            "value": "15",
            "transaction_id": 1190087,
            "created_at": "2018-11-17 13:46:06",
            "updated_at": "2018-11-17 13:46:06"
        },
        {
            "id": 241076,
            "address": "NQogc7UGuvF7VwzdWhjmtqf711azdmYLSY",
            "assetID": "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02",
            "value": "15",
            "transaction_id": 1190081,
            "created_at": "2018-11-17 13:45:07",
            "updated_at": "2018-11-17 13:45:07"
        },
        {
            "id": 241075,
            "address": "NSwSRrZvz1c75DhiDn6tA9AhondTP5x12U",
            "assetID": "4945ca009174097e6614d306b66e1f9cb1fce586cb857729be9e1c5cc04c9c02",
            "value": "15",
            "transaction_id": 1190075,
            "created_at": "2018-11-17 13:45:07",
            "updated_at": "2018-11-17 13:45:07"
        }
    ],
    "first_page_url": "http:\/\/localhost\/api\/outputs?page=1",
    "from": 1,
    "next_page_url": "http:\/\/localhost\/api\/outputs?page=2",
    "path": "http:\/\/localhost\/api\/outputs",
    "per_page": "5",
    "prev_page_url": null,
    "to": 5
}
```

### HTTP Request
`GET api/outputs`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    per_page |  optional  | Number of results per page
    txType |  optional  | Transaction type
    address |  optional  | NKN wallet address

<!-- END_8835d32f6c07647414279af547b0294f -->

#Payloads

Endpoints for querying transaction-payloads
<!-- START_769d6cb7df2d22ef0c88389254e1efe5 -->
## Get single payload

Returns a specific payload based on a transaction-database-id

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/payloads/{tId}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/payloads/{tId}",
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
    "id": 949076,
    "sigchain": "10441a2084c179dd18175e0ef6180fedb3866ed50298c24b976a8e76d71906081e440c162220f91c287958908c910c40791591da64c75296691dd0e7bd45fe113204850b65f42a21037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d3221037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d3a450a2093f7f6e2b8e53af0ccd356007c2beb6d2de8e7b57ec4a2bcab2babb5984dc703122102254f8cf6fa4db6ae24cb84dddb842733f1fc94942f7c49a2a7e57bb3cf886e783a89010a2093c242a8ce92a09267d00f63a2ddbf3789c0a552dd9bb87ae9d82cf88399c6fc122103c97def470c2096e506dec005e6520ae02bc88271ef4ed0d3ef1d397b54ad5db018012a40a211b447046fe0f746e90e517afb5e24dd06c22f120a36d39b11dd266f912c91a22d7781a503594be3e6b7286718ae4a3f9ffe9f20937c455d82c7ab2bcdc0353a89010a20d3d1dac943ba8668a2477f2f228b7e32a24d29e679a52bc9426bab084a785e34122103098fb8e266c3a5cb8519624d5f3cdff3f5431279476d2ef63bbf16d80627d9d518012a4028626993630c3c3ee71467263fdfb0e63d3427e7aec35ee1f88c8a47eded73f74eb2b5c4dab9d115d4939452be95c0ff78bdf5f1525d176b7bdcadb6d9ab9d723a89010a20f3dd214b14e5745d72576ef9311006310fbe9f94135f5c4420b40c6425ef2482122102bac5f29266d66cc9f2c7931d4248f857d691e3066edbe6ffe5ad1781256b9c2a18012a403ef0cb3c3de3b10edd90cbe49089ad52f5f7081481dfc67eacd1c1a1234bc2fe33a2c8c285a689ee3de7ce35170e610cf13b0f1c91d2e7baf3e937e890bba9dd3a89010a2004086825a35d3ce0706b7f46a53a92dbad2811446200cd9d83d1630e812201a1122102dc69b3af8f97b47e078fc82d4835621cb0be1b6d0ea98c69b6e9a17b99e768cd18012a40ef902a2d57517f639befc6c49d42095b1dbe2dd80cb1d7857d92b7c9417073c1076f371f9843f58491738b16cf4f2c935311d6a859232671719b2ac7d17d83153a89010a20049048ca3122c248ec1d6e7f1194398103420cbd708468afa0ed3d6f74dbe2bf1221037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d18012a4006b2dd4b088faede74d8fd987980ea691344df151f95e36261ca918b572450783ee8fe4451b7195681ddf104dca63d42e3407b4c078c71f479f73cba39bde63a3a450a2004ab4b2bf3fd74701847506a146847552443d781355eba9b346b17a97d7fa1201221037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d",
    "submitter": "35a84af592cf5c45099b1711931028d72d2fa54a",
    "transaction_id": 1190084,
    "created_at": "2018-11-17 13:45:08",
    "updated_at": "2018-11-17 13:45:08",
    "name": null,
    "registrant": null
}
```

### HTTP Request
`GET api/payloads/{tId}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    tId |  required  | The database-id of the according transaction

<!-- END_769d6cb7df2d22ef0c88389254e1efe5 -->

#Port check

Endpoints for the port checker
<!-- START_b1663b26b0b7b1e9521c22e23bcb5b72 -->
## Check port

Checks port 30001, 30002 and 30003 of a given IP or address

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/checkPort" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/checkPort",
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
    "30001": "open",
    "30002": "open",
    "30003": "open"
}
```

### HTTP Request
`GET api/checkPort`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    address |  required  | Address or hostname to check

<!-- END_b1663b26b0b7b1e9521c22e23bcb5b72 -->

#Statistics

Endpoints for NKN Network statistics
<!-- START_c34ed655f7134ffda670ff21b846cb9e -->
## Daily blocks

Shows the amount of blocks generated per day

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/statistics/daily/blocks" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/statistics/daily/blocks",
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
        "count": 2349,
        "date": "2018-11-17"
    },
    {
        "count": 4136,
        "date": "2018-11-16"
    },
    {
        "count": 4132,
        "date": "2018-11-15"
    },
    {
        "count": 4078,
        "date": "2018-11-14"
    },
    {
        "count": 4117,
        "date": "2018-11-13"
    },
    {
        "count": 3962,
        "date": "2018-11-12"
    },
    {
        "count": 3232,
        "date": "2018-11-11"
    },
    {
        "count": 2439,
        "date": "2018-11-10"
    }
]
```

### HTTP Request
`GET api/statistics/daily/blocks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    pubkey |  optional  | Only return blocks that were signed by the given public key

<!-- END_c34ed655f7134ffda670ff21b846cb9e -->

<!-- START_4dc7b77439279969ac864631ffa9ab13 -->
## Daily transactions

Shows the amount of transactions generated per day

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/statistics/daily/transactions" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/statistics/daily/transactions",
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
        "count": 9985,
        "date": "2018-11-17"
    },
    {
        "count": 17483,
        "date": "2018-11-16"
    },
    {
        "count": 17641,
        "date": "2018-11-15"
    },
    {
        "count": 17308,
        "date": "2018-11-14"
    },
    {
        "count": 17605,
        "date": "2018-11-13"
    },
    {
        "count": 17124,
        "date": "2018-11-12"
    },
    {
        "count": 14734,
        "date": "2018-11-11"
    },
    {
        "count": 10119,
        "date": "2018-11-10"
    }
]
```

### HTTP Request
`GET api/statistics/daily/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_4dc7b77439279969ac864631ffa9ab13 -->

<!-- START_54aa92c68880d36a9f37487e955bbe88 -->
## Daily transfers

Shows the amount of transfers generated per day

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/statistics/daily/transfers" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/statistics/daily/transfers",
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
        "count": 6,
        "date": "2018-11-17"
    },
    {
        "count": 15,
        "date": "2018-11-16"
    },
    {
        "count": 6,
        "date": "2018-11-15"
    },
    {
        "count": 4,
        "date": "2018-11-14"
    },
    {
        "count": 16,
        "date": "2018-11-13"
    },
    {
        "count": 5,
        "date": "2018-11-12"
    },
    {
        "count": 8,
        "date": "2018-11-11"
    },
    {
        "count": 5,
        "date": "2018-11-10"
    }
]
```

### HTTP Request
`GET api/statistics/daily/transfers`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_54aa92c68880d36a9f37487e955bbe88 -->

<!-- START_6ed67af2949ddb7b1bd68c857a1b6b76 -->
## All miners

Shows the amount of mined blocks by all wallet addresses

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/statistics/miners" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/statistics/miners",
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
        "address": "NgCckkmDFZmEDtwvZMNtC2ruVydYKRWpAB",
        "total": 29271
    },
    {
        "address": "NUnC5kb7XVosyzryGYARSceGAzmcrEWBuw",
        "total": 14828
    },
    {
        "address": "NWdPmJycdGvNY3VNKHsJUsEpGwmSXCzNVU",
        "total": 6600
    },
    {
        "address": "NQogc7UGuvF7VwzdWhjmtqf711azdmYLSY",
        "total": 6361
    },
    {
        "address": "NU2t28NmBoGe1bZ9LWsCxEN4B5hAa4Zwb8",
        "total": 6236
    },
    {
        "address": "NVXifSiRNrofzLMojNjLG56RmKHQkGhWPZ",
        "total": 5351
    },
    {
        "address": "NMbGMaugVANhQBtgQnQAa7GAUTKVcapFUJ",
        "total": 4423
    },
    {
        "address": "NhWjY9iwD5Ad8DafiEWbTo4buLpBomdttH",
        "total": 3687
    }
]
```

### HTTP Request
`GET api/statistics/miners`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned

<!-- END_6ed67af2949ddb7b1bd68c857a1b6b76 -->

#Transactions

Endpoints for querying transactions
<!-- START_9af0b9f04f16a1c9705c5300772f6f16 -->
## Get all transactions

Returns all transactions with corresponding headers, payloads, outputs, inputs and attributes in simple pagination format starting with the latest one

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/transactions" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/transactions",
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
    "current_page": 1,
    "data": [
        {
            "id": 1190133,
            "hash": "eb4905c511b727ff2982b014252a18adf63b413bf1c56bdc60409e33fb33a2f9",
            "payloadVersion": 0,
            "txType": 0,
            "block_id": 240770,
            "sender": null,
            "created_at": "2018-11-17 13:49:07",
            "updated_at": "2018-11-17 13:49:07",
            "payload": null,
            "outputs": [],
            "inputs": [],
            "attributes": [],
            "block": null
        },
        {
            "id": 1190129,
            "hash": "139f30ee1dd8546bbf086f375907a9f5a8dc4410d261b1b26b77f0dc67b9afdb",
            "payloadVersion": 0,
            "txType": 0,
            "block_id": 240769,
            "sender": null,
            "created_at": "2018-11-17 13:49:07",
            "updated_at": "2018-11-17 13:49:07",
            "payload": null,
            "outputs": [],
            "inputs": [],
            "attributes": [],
            "block": null
        },
        {
            "id": 1190120,
            "hash": "9d2fde9241789dcd25c8d243208a86b4cd2a5d670bfa6c762e322f04034df09a",
            "payloadVersion": 0,
            "txType": 0,
            "block_id": 240768,
            "sender": null,
            "created_at": "2018-11-17 13:49:06",
            "updated_at": "2018-11-17 13:49:06",
            "payload": null,
            "outputs": [],
            "inputs": [],
            "attributes": [],
            "block": null
        },
        {
            "id": 1190116,
            "hash": "e9a7ebff8d09d915e1603f3a374f255d6d223c1526e50b28051a3e9e0b9a7341",
            "payloadVersion": 0,
            "txType": 0,
            "block_id": 240767,
            "sender": null,
            "created_at": "2018-11-17 13:48:11",
            "updated_at": "2018-11-17 13:48:11",
            "payload": null,
            "outputs": [],
            "inputs": [],
            "attributes": [],
            "block": null
        }
    ],
    "first_page_url": "http:\/\/localhost\/api\/transactions?page=1",
    "from": 1,
    "next_page_url": "http:\/\/localhost\/api\/transactions?page=2",
    "path": "http:\/\/localhost\/api\/transactions",
    "per_page": "4",
    "prev_page_url": null,
    "to": 4
}
```

### HTTP Request
`GET api/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    latest |  optional  | Limits the results returned
    per_page |  optional  | Number of results per page
    txType |  optional  | Filter results by txType - single or comma separated
    address |  optional  | Filter results by NKN address
    withoutPayload |  optional  | remove payload
    withoutOutputs |  optional  | remove outputs
    withoutInputs |  optional  | remove inputs
    withoutAttributes |  optional  | remove attributes

<!-- END_9af0b9f04f16a1c9705c5300772f6f16 -->

<!-- START_a1c865187f65a7d510663760dcd6d5c4 -->
## Get single transaction by hash

Returns a specific block based on the height or block hash

> Example request:

```bash
curl -X GET -G "https://nknx.org/api/transactions/{tHash}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/transactions/{tHash}",
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
    "id": 1190084,
    "hash": "9946d19e6a2fa035e3456de5836207569b11204593783f62f7838ea99d41fcf2",
    "payloadVersion": 0,
    "txType": 66,
    "block_id": 240758,
    "sender": null,
    "created_at": "2018-11-17 13:45:08",
    "updated_at": "2018-11-17 13:45:08",
    "payload": {
        "id": 949076,
        "sigchain": "10441a2084c179dd18175e0ef6180fedb3866ed50298c24b976a8e76d71906081e440c162220f91c287958908c910c40791591da64c75296691dd0e7bd45fe113204850b65f42a21037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d3221037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d3a450a2093f7f6e2b8e53af0ccd356007c2beb6d2de8e7b57ec4a2bcab2babb5984dc703122102254f8cf6fa4db6ae24cb84dddb842733f1fc94942f7c49a2a7e57bb3cf886e783a89010a2093c242a8ce92a09267d00f63a2ddbf3789c0a552dd9bb87ae9d82cf88399c6fc122103c97def470c2096e506dec005e6520ae02bc88271ef4ed0d3ef1d397b54ad5db018012a40a211b447046fe0f746e90e517afb5e24dd06c22f120a36d39b11dd266f912c91a22d7781a503594be3e6b7286718ae4a3f9ffe9f20937c455d82c7ab2bcdc0353a89010a20d3d1dac943ba8668a2477f2f228b7e32a24d29e679a52bc9426bab084a785e34122103098fb8e266c3a5cb8519624d5f3cdff3f5431279476d2ef63bbf16d80627d9d518012a4028626993630c3c3ee71467263fdfb0e63d3427e7aec35ee1f88c8a47eded73f74eb2b5c4dab9d115d4939452be95c0ff78bdf5f1525d176b7bdcadb6d9ab9d723a89010a20f3dd214b14e5745d72576ef9311006310fbe9f94135f5c4420b40c6425ef2482122102bac5f29266d66cc9f2c7931d4248f857d691e3066edbe6ffe5ad1781256b9c2a18012a403ef0cb3c3de3b10edd90cbe49089ad52f5f7081481dfc67eacd1c1a1234bc2fe33a2c8c285a689ee3de7ce35170e610cf13b0f1c91d2e7baf3e937e890bba9dd3a89010a2004086825a35d3ce0706b7f46a53a92dbad2811446200cd9d83d1630e812201a1122102dc69b3af8f97b47e078fc82d4835621cb0be1b6d0ea98c69b6e9a17b99e768cd18012a40ef902a2d57517f639befc6c49d42095b1dbe2dd80cb1d7857d92b7c9417073c1076f371f9843f58491738b16cf4f2c935311d6a859232671719b2ac7d17d83153a89010a20049048ca3122c248ec1d6e7f1194398103420cbd708468afa0ed3d6f74dbe2bf1221037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d18012a4006b2dd4b088faede74d8fd987980ea691344df151f95e36261ca918b572450783ee8fe4451b7195681ddf104dca63d42e3407b4c078c71f479f73cba39bde63a3a450a2004ab4b2bf3fd74701847506a146847552443d781355eba9b346b17a97d7fa1201221037ab46c527ab056c7f62f29c62a564707f900964ae22621a7dbbfb45afcd07b1d",
        "submitter": "35a84af592cf5c45099b1711931028d72d2fa54a",
        "transaction_id": 1190084,
        "created_at": "2018-11-17 13:45:08",
        "updated_at": "2018-11-17 13:45:08",
        "name": null,
        "registrant": null
    },
    "outputs": [],
    "inputs": [],
    "attributes": [
        {
            "id": 1190084,
            "data": "a50c6edc50f054718e59939c6a444b26f2df83f794fdbe82e55047382109388b",
            "usage": 0,
            "transaction_id": 1190084,
            "created_at": "2018-11-17 13:45:08",
            "updated_at": "2018-11-17 13:45:08"
        }
    ],
    "block": {
        "id": 240758,
        "hash": "0a325a5c0e94cb77529ff80045c44209714f03663f3c58d78e0a81780720a137",
        "transaction_count": 6,
        "created_at": "2018-11-17 13:45:07",
        "updated_at": "2018-11-17 13:45:07",
        "header": {
            "id": 240758,
            "chordId": "",
            "hash": "0000000000000000000000000000000000000000000000000000000000000000",
            "height": 240758,
            "prevBlockHash": "4e523b1071b070b9f7e2d0ef0e5e681a44f6ee6d21c5cb94b6e5cb57909ebd9f",
            "signature": "e4d17c56827ffd88095fa699dd2e2754d712b15bd3bf232b1928a487495f2dce895f917f1a0ba45c8949ea8e29d19a864d2d922a5572822eccf64cb30c656286",
            "signer": "02dc69b3af8f97b47e078fc82d4835621cb0be1b6d0ea98c69b6e9a17b99e768cd",
            "timestamp": "2018-11-17 13:44:44",
            "transactionsRoot": "81c5a667d0bec0a2e36a9a7ff5a2ddf70e29ba069e3bbefcf91a67c78c1dae61",
            "version": 1,
            "winningHash": "b4b016a64a7c9e0b35a0eab3ed18899da62afeb10e39b8a8e8c27cd52ae6ed67",
            "winningHashType": 1,
            "block_id": 240758,
            "created_at": "2018-11-17 13:45:07",
            "updated_at": "2018-11-17 13:45:07"
        }
    }
}
```

### HTTP Request
`GET api/transactions/{tHash}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    block_id |  required  | Id of the resource
    withoutPayload |  optional  | remove payload
    withoutOutputs |  optional  | remove outputs
    withoutInputs |  optional  | remove inputs
    withoutAttributes |  optional  | remove attributes

<!-- END_a1c865187f65a7d510663760dcd6d5c4 -->

#User Nodes

Endpoints for querying user nodes
<!-- START_cc15f7f0d3c2151ff486d4817f0af4d2 -->
## Get all nodes
Returns all stored nodes of currently logged in user

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/nodes" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/nodes",
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
    },
    {
        "id": 173,
        "label": "nknx1",
        "state": 0,
        "syncState": "PersistFinished",
        "port": 0,
        "nodePort": 0,
        "chordPort": null,
        "jsonPort": 30003,
        "wsPort": 30002,
        "addr": "159.65.200.221",
        "time": 1541687107532520465,
        "version": 0,
        "services": null,
        "relay": 0,
        "height": 0,
        "txnCnt": 1,
        "rxTxnCnt": 8510,
        "chordID": "60665856ba544df2ceb7e8a4047f35872a82c8a1056fe3fdc00ca233b651c212",
        "softwareVersion": "0.5.2-alpha",
        "latestBlockHeight": 207012,
        "online": 1,
        "user_id": 2,
        "created_at": "2018-11-04 15:52:12",
        "updated_at": "2018-11-17 12:50:24"
    }
]
```

### HTTP Request
`GET api/nodes`


<!-- END_cc15f7f0d3c2151ff486d4817f0af4d2 -->

<!-- START_64af9fc87a58a3bccf79a2764ed105da -->
## Store wallet
Store one or multiple nodes in the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://nknx.org/api/nodes"     -d "ip"="104.248.138.60" \
    -d "label"="nknx" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/nodes",
    "method": "POST",
    "data": {
        "ip": "104.248.138.60",
        "label": "nknx"
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
        "saved": [
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
                "addr": "104.248.138.60",
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
        ],
        "failed": [
            "192.168.178.44",
            "192.168.178.32"
        ],
        "duplicate": [
            "192.168.178.44",
            "192.168.178.32"
        ]
    }
}
```

### HTTP Request
`POST api/nodes`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    ip | string |  required  | One or multiple ips/domain names (comma separated) to store in the database
    label | string |  optional  | An optional label of the node

<!-- END_64af9fc87a58a3bccf79a2764ed105da -->

<!-- START_90c99c42b5fb29dd48cace12c2d389f9 -->
## Get single node by id
Returns a specific user-node based on the id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/nodes/{node}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/nodes/{node}",
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
`GET api/nodes/{node}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    node |  required  | Id of the resource

<!-- END_90c99c42b5fb29dd48cace12c2d389f9 -->

<!-- START_5ff07985d5fc094017ac501ce96aab41 -->
## Remove single node by id
Remove the specified user-node from the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://nknx.org/api/nodes/{node}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/nodes/{node}",
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
`DELETE api/nodes/{node}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    node |  required  | Id of the resource

<!-- END_5ff07985d5fc094017ac501ce96aab41 -->

<!-- START_f2e4d2ceec7b9753c87a6323164480ed -->
## Remove all user-nodes
Remove all user-nodes from the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://nknx.org/api/nodes" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/nodes",
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
`DELETE api/nodes`


<!-- END_f2e4d2ceec7b9753c87a6323164480ed -->

#User Wallets

Endpoints for querying stored user wallets
<!-- START_6d8b841135233d585a7e4e39d6e69901 -->
## Get all wallets
Returns all stored wallets of currently logged in user

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/walletAddresses" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/walletAddresses",
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
        "id": 1,
        "label": "nknx",
        "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
        "balance": 2541,
        "user_id": 1,
        "created_at": "2018-11-17 09:42:58",
        "updated_at": "2018-11-17 09:42:58"
    },
    {
        "id": 2,
        "label": "nknx",
        "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
        "balance": 2341,
        "user_id": 1,
        "created_at": "2018-11-17 09:42:58",
        "updated_at": "2018-11-17 09:42:58"
    }
]
```

### HTTP Request
`GET api/walletAddresses`


<!-- END_6d8b841135233d585a7e4e39d6e69901 -->

<!-- START_ff8afd94b3c8d89d01c499c5bd7f9159 -->
## Store wallet
Store a wallet in the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X POST "https://nknx.org/api/walletAddresses"     -d "address"="C98uRMaecjeshsn7" \
    -d "label"="nknx" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/walletAddresses",
    "method": "POST",
    "data": {
        "address": "C98uRMaecjeshsn7",
        "label": "nknx"
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
        "id": 2,
        "label": "nknx",
        "address": "NNP6M8EGZcWSZNgA2ebQfMVyNkwX6XXXXX",
        "balance": 2341,
        "user_id": 1,
        "created_at": "2018-11-17 09:42:58",
        "updated_at": "2018-11-17 09:42:58"
    }
}
```

### HTTP Request
`POST api/walletAddresses`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    address | string |  required  | NKN wallet address
    label | string |  optional  | An optional label of the node

<!-- END_ff8afd94b3c8d89d01c499c5bd7f9159 -->

<!-- START_085a2ed537284f6bf58231bb435022c2 -->
## Get single wallet by walletAddress
Returns a specific user-wallet based on the address

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/walletAddresses/{walletAddress}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/walletAddresses/{walletAddress}",
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
`GET api/walletAddresses/{walletAddress}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  required  | Id of the resource

<!-- END_085a2ed537284f6bf58231bb435022c2 -->

<!-- START_5989994a00d1fcea4ac656d4272e2196 -->
## Remove single wallet by id
Remove the specified user-wallet from the database

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X DELETE "https://nknx.org/api/walletAddresses/{walletAddress}" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/walletAddresses/{walletAddress}",
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
`DELETE api/walletAddresses/{walletAddress}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  required  | Id of the resource

<!-- END_5989994a00d1fcea4ac656d4272e2196 -->

<!-- START_05bb4388754f3f3028402a26349dbcbe -->
## Get mining output per day
Get the daily mining output based on a database-wallet id

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```bash
curl -X GET -G "https://nknx.org/api/walletAddresses/{walletAddress}/miningOutput" 
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://nknx.org/api/walletAddresses/{walletAddress}/miningOutput",
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
`GET api/walletAddresses/{walletAddress}/miningOutput`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    walletAddress |  required  | Id of the resource
    latest |  optional  | Limits the days returned

<!-- END_05bb4388754f3f3028402a26349dbcbe -->


