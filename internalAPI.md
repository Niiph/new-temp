# Internal API

Those endpoints are designed to be user for communication between backend and frontend.  
- [Authorization](#authorization)
  - [Register](#register)
  - [Login](#login)
- [Devices](#devices)

## Authorization

### Register

* POST `api/register`
* Input: 
```json5
{
    "username": "string",
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
    "token": "string/JWT Token"
}
```

## Devices

### List

* Return list of user's active devices with active sensors
* GET `api/sensors`
* Output:
```json5
{
  "hydra:member": [
    {
      "id": "string/device uuid",
      "name": "string",
      "sensors": [
        {
          "id": "string/sensor uuid",
          "name": "string",
        }
      ]
    }
  ]
}
```

### Full list

* GET `api/devices/full_list`

### View

* Get `api/devices/{id}`
