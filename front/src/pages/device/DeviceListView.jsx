import { useEffect, useState } from 'react';
import { useParams, Link as RouterLink } from 'react-router-dom';
import { Fab, Grid, Switch, Typography, Popover, Button, TextField } from '@mui/material';
import { styled } from '@mui/system';
import MainCard from 'components/MainCard';
import axios from 'axios';
import Links from 'routes/ApiRoutes';
import * as Icons from '@ant-design/icons';
import AddIcon from '@mui/icons-material/Add';
import { usePopupState, bindTrigger, bindPopover } from 'material-ui-popup-state/hooks';
import { Post, Get } from '../../components/ApiRequest';

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

const DeviceInfo = styled('div')({
    display: 'flex',
    alignItems: 'center',
    gap: '8px'
});

const DeviceListView = () => {
    const { id } = useParams();
    const [devices, setDevices] = useState(null);
    const popupState = usePopupState({ variant: 'popover', popupId: 'device-popover' });
    const [inputValue, setInputValue] = useState('');

    const fetchDevicesData = async () => {
        try {
            const response = await Get(Links('devicesFullList'));
            setDevices(response['hydra:member']);
        } catch (error) {
            console.error('Error fetching devices data:', error);
        }
    };

    useEffect(() => {
        fetchDevicesData();
    }, [id]);

    const handleAddDeviceConfirm = async () => {
        try {
            await Post(Links('devices'), { name: inputValue });
            popupState.close();
            await fetchDevicesData();
        } catch (error) {
            console.error('Error creating new device:', error);
        }
    };

    if (!devices) {
        return <div>Loading...</div>; // Placeholder for when data is loading
    }

    return (
        <Grid>
            <Grid container spacing={2}>
                {devices.map((device) => (
                    <Grid item key={device.id} xs={12} sm={6} md={4}>
                        <RouterLink to={`/device/${device.id}`} style={{ textDecoration: 'none' }}>
                            <MainCard>
                                <CardContent>
                                    <TitleContainer>
                                        <Title>{device.name}</Title>
                                        <Switch label="" color="success" defaultChecked={!!device.active} disabled />
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
                                    </DeviceInfo>
                                </CardContent>
                            </MainCard>
                        </RouterLink>
                    </Grid>
                ))}
            </Grid>
            <div>
                <Fab color="primary" aria-label="add" sx={{ mt: 2 }} {...bindTrigger(popupState)}>
                    <AddIcon />
                </Fab>
                <Popover
                    {...bindPopover(popupState)}
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
                        <Button onClick={handleAddDeviceConfirm}>Confirm</Button>
                    </Grid>
                </Popover>
            </div>
        </Grid>
    );
};

export default DeviceListView;
