import React, { useEffect, useState } from 'react';
import { Link as RouterLink, useParams } from 'react-router-dom';
import { Grid, Switch, Typography, TextField, Popover, Button, Fab } from '@mui/material';
import { styled } from '@mui/system';
import MainCard from 'components/MainCard';
import { Get, Post, Put } from 'components/ApiRequest';
import Links from 'routes/ApiRoutes';
import * as Icons from '@ant-design/icons';
import EditableTextField from 'components/EditableTextField';
import { usePopupState, bindTrigger, bindPopover } from 'material-ui-popup-state/hooks';
import AddIcon from '@mui/icons-material/Add';

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

const Title = styled('div')(({ isEditing }) => ({
    fontSize: '1.5rem',
    fontWeight: 'bold',
    cursor: isEditing ? 'default' : 'pointer'
}));

const DeviceInfo = styled(Typography)({
    display: 'flex',
    alignItems: 'center',
    gap: '8px'
});

const DeviceView = () => {
    const { id } = useParams();
    const [device, setDevice] = useState(null);
    const [isActive, setIsActive] = useState(false);
    const [editedTitle, setEditedTitle] = useState('');
    const resetPopupState = usePopupState({ variant: 'popover', popupId: 'password-popover' });
    const addPopupState = usePopupState({ variant: 'popover', popupId: 'add-popover' });
    const [inputValue, setInputValue] = useState('');

    const fetchDeviceData = async () => {
        try {
            const response = await Get(Links(`devices`, id));
            setDevice(response);
            setIsActive(response.active);
            setEditedTitle(response.name);
        } catch (error) {
            console.error('Error fetching device data:', error);
        }
    };

    useEffect(() => {
        fetchDeviceData();
    }, [id]);

    const handleSwitchChange = async () => {
        try {
            const updatedIsActive = !isActive; // Toggle the active state
            setIsActive(updatedIsActive); // Update the state immediately

            const payload = {
                active: updatedIsActive // Use the updated value
            };

            await Put(Links('deviceChangeActive', id), payload);
        } catch (error) {
            console.error('Error updating device active state:', error);
        }
    };

    const handleGeneratePasswordConfirm = async () => {
        try {
            setDevice(await Put(Links('deviceChangePassword', id), {}));
            resetPopupState.close();
        } catch (error) {
            console.error('Error generating new password:', error);
        }
    };

    const handleAddSensorConfirm = async () => {
        try {
            await Post(Links('sensors'), { name: inputValue, deviceId: device.id });
            addPopupState.close();
            await fetchDeviceData();
        } catch (error) {
            console.error('Error creating new device:', error);
        }
    };

    if (!device) {
        return <div>Loading...</div>; // Placeholder for when data is loading
    }

    const sensors = (
        <Grid container spacing={2}>
            {device.sensors.map((sensor) => (
                <Grid item key={sensor.id} xs={12} sm={6} md={4}>
                    <RouterLink to={`/sensor/${sensor.id}`} style={{ textDecoration: 'none' }}>
                        <MainCard>
                            <CardContent>
                                <TitleContainer>
                                    <Title>{sensor.name}</Title>
                                    <Switch label="" color="success" defaultChecked={!!sensor.active} disabled />
                                </TitleContainer>
                            </CardContent>
                        </MainCard>
                    </RouterLink>
                </Grid>
            ))}
        </Grid>
    );

    return (
        <Grid>
            <MainCard>
                <CardContent>
                    <TitleContainer>
                        <Title>
                            <EditableTextField value={editedTitle} property="name" url={Links('deviceChangeName', id)} />
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
                            <strong>Short ID:</strong>
                        </Typography>
                        <Typography variant="body1">{device.shortId}</Typography>
                    </DeviceInfo>
                    <DeviceInfo>
                        <Typography variant="body1">
                            <strong>Password:</strong>
                        </Typography>
                        <Typography variant="body1">{device.password}</Typography>
                        <div>
                            <Button {...bindTrigger(resetPopupState)}>
                                <Icons.RedoOutlined style={{ color: 'red' }} />
                            </Button>
                            <Popover
                                {...bindPopover(resetPopupState)}
                                anchorOrigin={{
                                    vertical: 'center',
                                    horizontal: 'right'
                                }}
                                transformOrigin={{
                                    vertical: 'center',
                                    horizontal: 'left'
                                }}
                            >
                                <Typography sx={{ p: 2 }}>
                                    <Typography variant="body1">Are you sure you want to generate a new password?</Typography>
                                    <Button onClick={handleGeneratePasswordConfirm}>Confirm</Button>
                                </Typography>
                            </Popover>
                        </div>
                    </DeviceInfo>
                </CardContent>
            </MainCard>
            <Typography variant="h4">
                <br />
                <strong>Sensors:</strong>
            </Typography>
            {sensors}
            <div>
                <Fab Fab color="primary" aria-label="add" sx={{ mt: 2 }} {...bindTrigger(addPopupState)}>
                    <AddIcon />
                </Fab>
                <Popover
                    {...bindPopover(addPopupState)}
                    anchorOrigin={{
                        vertical: 'center',
                        horizontal: 'right'
                    }}
                    transformOrigin={{
                        vertical: 'center',
                        horizontal: 'left'
                    }}
                >
                    <Grid sx={{ p: 2 }}>
                        <TextField required id="name" label="Name" onChange={(e) => setInputValue(e.target.value)} />
                        <Button onClick={handleAddSensorConfirm}>Confirm</Button>
                    </Grid>
                </Popover>
            </div>
        </Grid>
    );
};

export default DeviceView;
