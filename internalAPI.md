# Internal API

Those endpoints are designed to be user for communication between backend and frontend.  
- [Authorization](#authorization)
  - [Register](#register)
  - [Login](#login)
- [Devices](#devices)
  - [View](#devices-view)
  - [List](#devices-list)
  - [Full list](#devices-full-list)

## Authorization

### Register

* POST `api/register`
* Input: 
```json5
{
    "username": "string/email",
    "password": "string"
}
```

### Login

* Returns token required to access all endpoints (Authorization: Bearer)
* POST `api/login_check`
* Input:
```json5
{
    "username": "string",
    "password": "string"
}
```
* Output:
```json5
{
    "token": "string/JWT Token",
    "refresh_token_expiration": "unix timestamp"
}
```

## Devices

### <a id="devices-view"></a>View

* Get `api/devices/{id}`
* Output:
```json5
{
    "id": "string/uuid",
    "name": "string",
    "active": "boolean",
    "shortId": "string",
    'password': 'string',
    "sensors": [
        {
            "id": "string/uuid",
            "name": "string",
            "active": "boolean"
        }
    ]
}
```

### <a id="devices-list"></a>List

* Returns list of user's active devices with active sensors
* GET `api/sensors`
* Output:
```json5
{
    "id": "string/uuid",
    "name": "string",
    "sensors": [
        {
            "id": "string/sensor uuid",
            "name": "string"
        }
    ]
}
```

### <a id="devices-full-list"></a>Full list

* Returns full list of user's devices with
* GET `api/sensors/full_list`
* Output:
```json5
[
    {
        "id": "string/uuid",
        "name": "string",
        "active": "boolean",
        "shortId": "string",
        "password": "string"
    }
]
```

### <a id="devices-create"></a>Create

* Changes device's `name` parameter
* POST `api/sensors`
* Input:
```json5
{
    "name": "string(25)"
}
```
* Output:
```json5
[
    {
        "id": "string/uuid",
        "name": "string",
        "active": false,
        "shortId": "string",
        "password": "string",
        "sensors" : []
    }
]
```

### <a id="devices-change-active"></a>Change active

* Changes device's `active` parameter
* GET `api/sensors/{id}/change_active`
* Input: 
```json5
{
    "active": "boolean"
}
```
* Output:
```json5
[
    {
        "id": "string/uuid",
        "name": "string",
        "active": "boolean",
        "shortId": "string",
        "password": "string",
        "sensors" : [
            {
                "id": "string/sensor uuid",
                "name": "string",
                "active": "boolean"
            }
        ]
    }
]
```

### <a id="devices-change-name"></a>Change name

* Changes device's `name` parameter
* GET `api/sensors/{id}/change_name`
* Input:
```json5
{
    "name": "string(25)"
}
```
* Output:
```json5
[
    {
        "id": "string/uuid",
        "name": "string",
        "active": "boolean",
        "shortId": "string",
        "password": "string",
        "sensors" : [
            {
                "id": "string/sensor uuid",
                "name": "string",
                "active": "boolean"
            }
        ]
    }
]
```
