## External API

Those endpoints are designed to be used exclusively by external devices.  
There is flow that should be followed step by step:

1. [Time request](#time-request)
2. [Sensors list request](#sensors-list-request)
3. [Send readings one by one](#send-reading)

### Time request

* POST `/api/time`
* Input: device short ID:
    ```json5
    {
        "shortId": "H4RGYLKDWW7N6BWCDMQHNZAHW2"
    }
    ```
* Output - date in ISO8601:
    ```json5
    {
        "date": "2023-05-13T11:32:23+00:00"
    }
    ```
* Returned time string is transformed into device token using device  
short ID and device password: 
    ```php
    hash(
        'sha256',
        sprintf(
            '%s_%s_%s',
            $device->getDevicePassword(),
            $device->getShortId(),
            $this->createdAt->toIso8601String()
        );
    ```
* Calculated token is valid for 60 minutes

### Sensors list request

* GET `api/devices/{deviceShortID}/list_sensors`
* Header: `X-Authentication-Token` and calculated device token
* Output - list of sensor IDs: 
    ```json5
    [
        {
            "id": "85bce092-2a21-4629-9b77-349f9fdf55f8"
        },
        {
            "id": "bc62f6fd-401f-41a8-9059-3a17d7776848"
        }
    ]
    ```

### Send reading

* PUT `api/sensors/{sensorId}/add_reading` 
* Header: `X-Authentication-Token` and calculated device token
* Input - contains numeric value and reading type symbol (1-8 characters)
```json5
{
    "value": 15.5,
    "type": "T"
}
```
