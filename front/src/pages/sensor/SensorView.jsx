import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { Switch, Typography, Autocomplete, Grid, TextField } from '@mui/material';
import { styled } from '@mui/system';
import MainCard from 'components/MainCard';
import Links from 'routes/ApiRoutes';
import EditableTextField from 'components/EditableTextField';
import { Get, Put } from '../../components/ApiRequest';

const CardContent = styled('div')({
    display: 'flex',
    flexDirection: 'column',
    gap: '16px',
    padding: '16px'
});

const TitleContainer = styled('div')({
    display: 'flex',
    alignItems: 'center',
    gap: '8px'
});

const Title = styled(Typography)({
    fontSize: '1.5rem',
    fontWeight: 'bold'
});

const DeviceInfo = styled(Typography)({
    display: 'flex',
    alignItems: 'center',
    gap: '8px'
});

const config = {
    headers: {
        Authorization: 'Bearer ' + localStorage.getItem('jwt_token')
    }
};

const SensorView = () => {
    const { id } = useParams();
    const [sensor, setSensor] = useState(null);
    const [isActive, setIsActive] = useState(false);
    const [editedTitle, setEditedTitle] = useState('');
    const [editedPin, setEditedPin] = useState(null);
    const [editedAddress, setEditedAddress] = useState('');
    const [editedMinimum, setEditedMinimum] = useState(null);
    const [editedMaximum, setEditedMaximum] = useState(null);
    const [editedDevice, setEditedDevice] = useState('');
    const [isEditingDevice, setIsEditingDevice] = useState(false);
    const [devices, setDevices] = useState(null);

    useEffect(() => {
        const fetchDeviceData = async () => {
            try {
                const response = await Get(Links(`sensors`, id));

                setSensor(response);
                setIsActive(response.active);
                setEditedTitle(response.name);
                setEditedPin(response.pin);
                setEditedAddress(response.address);
                setEditedMinimum(response.minimum);
                setEditedMaximum(response.maximum);
                setEditedDevice(response.device);

                const devicesResponse = await Get(Links('devicesSimpleList'));
                setDevices(devicesResponse['hydra:member']);
            } catch (error) {
                console.error('Error fetching sensor data:', error);
            }
        };

        fetchDeviceData();
    }, [id]);

    const Devices = devices
        ? devices.map((device) => ({
              id: device.id,
              label: device.name,
              name: device.name
          }))
        : [];

    const handleSwitchChange = async () => {
        try {
            const updatedIsActive = !isActive; // Toggle the active state
            setIsActive(updatedIsActive); // Update the state immediately

            const payload = {
                active: updatedIsActive // Use the updated value
            };

            await Put(Links('sensorChangeActive', id), payload);
        } catch (error) {
            console.error('Error updating sensor active state:', error);
        }
    };

    const handleFieldClick = () => {
        setIsEditingDevice(true);
    };

    const handleFieldBlur = () => {
        setIsEditingDevice(false);
        const payload = { deviceId: editedDevice.id };
        Put(Links('sensorChangeDevice', id), payload);
    };

    if (!sensor) {
        return <div>Loading...</div>; // Placeholder for when data is loading
    }

    return (
        <MainCard>
            <CardContent>
                <TitleContainer>
                    <Title>
                        <EditableTextField value={editedTitle} property="name" url={Links('sensorChangeName', id)} />
                    </Title>
                    <Switch
                        label=""
                        color="success"
                        checked={isActive}
                        onChange={() => {
                            setIsActive((prevIsActive) => !prevIsActive);
                            handleSwitchChange();
                        }}
                    />
                </TitleContainer>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Pin:</strong>
                    </Typography>
                    <Typography variant="body1" color={sensor.pin ? 'textPrimary' : 'textSecondary'}>
                        <EditableTextField value={editedPin} property="pin" url={Links('sensorChangePin', id)} nullable={true} />
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Address:</strong>
                    </Typography>
                    <Typography variant="body1" color={sensor.address ? 'textPrimary' : 'textSecondary'}>
                        <EditableTextField
                            value={editedAddress}
                            property="address"
                            url={Links('sensorChangeAddress', id)}
                            nullable={true}
                        />
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Minimum:</strong>
                    </Typography>
                    <Typography variant="body1" color={sensor.minimum ? 'textPrimary' : 'textSecondary'}>
                        <EditableTextField
                            value={editedMinimum}
                            property="minimum"
                            url={Links('sensorChangeMinimum', id)}
                            nullable={true}
                        />
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Maximum:</strong>
                    </Typography>
                    <Typography variant="body1" color={sensor.maximum ? 'textPrimary' : 'textSecondary'}>
                        <EditableTextField
                            value={editedMaximum}
                            property="maximum"
                            url={Links('sensorChangeMaximum', id)}
                            nullable={true}
                        />
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Device:</strong>
                    </Typography>
                    <Grid>
                        {isEditingDevice ? (
                            <Autocomplete
                                disablePortal
                                id="combo-box-demo"
                                options={Devices}
                                sx={{ width: 300 }}
                                onBlur={handleFieldBlur}
                                onChange={(event, newValue) => {
                                    setEditedDevice(newValue);
                                }}
                                // onInputChange={(event, newInputValue) => {
                                //     setEditedDevice(newInputValue);
                                // }}
                                renderInput={(params) => <TextField {...params} label="Device" />}
                            />
                        ) : (
                            <Typography variant="body1" color={'textPrimary'} onClick={handleFieldClick}>
                                {editedDevice.name}
                            </Typography>
                        )}
                    </Grid>
                </DeviceInfo>
            </CardContent>
        </MainCard>
    );
};

export default SensorView;
