import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { Switch, Typography } from '@mui/material';
import { styled } from '@mui/system';
import MainCard from 'components/MainCard';
import axios from 'axios';
import Links from 'routes/ApiRoutes';
import * as Icons from '@ant-design/icons';

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
    const [device, setDevice] = useState(null);
    const [isActive, setIsActive] = useState(false);

    useEffect(() => {
        const fetchDeviceData = async () => {
            try {
                const response = await axios.get(Links(`sensors`, id), config);
                setDevice(response.data);
                setIsActive(response.data.active);
            } catch (error) {
                console.error('Error fetching device data:', error);
            }
        };

        fetchDeviceData();
    }, [id]);

    const handleSwitchChange = async () => {
        try {
            const updatedIsActive = !isActive; // Toggle the active state
            setIsActive(updatedIsActive); // Update the state immediately

            const payload = {
                active: updatedIsActive // Use the updated value
            };

            await axios.put(Links('sensorChangeActive', id), payload, config);
        } catch (error) {
            console.error('Error updating device active state:', error);
        }
    };

    if (!device) {
        return <div>Loading...</div>; // Placeholder for when data is loading
    }

    return (
        <MainCard>
            <CardContent>
                <TitleContainer>
                    <Title>{device.name}</Title>
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
                    <Typography variant="body1" color={device.pin ? 'textPrimary' : 'textSecondary'}>
                        {device.pin ?? 'empty'}
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Address:</strong>
                    </Typography>
                    <Typography variant="body1" color={device.address ? 'textPrimary' : 'textSecondary'}>
                        {device.address ?? 'empty'}
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Minimum:</strong>
                    </Typography>
                    <Typography variant="body1" color={device.minimum ? 'textPrimary' : 'textSecondary'}>
                        {device.minimum ?? 'empty'}
                    </Typography>
                </DeviceInfo>
                <DeviceInfo>
                    <Typography variant="body1">
                        <strong>Maximum:</strong>
                    </Typography>
                    <Typography variant="body1" color={device.maximum ? 'textPrimary' : 'textSecondary'}>
                        {device.maximum ?? 'empty'}
                    </Typography>
                </DeviceInfo>
            </CardContent>
        </MainCard>
    );
};

export default SensorView;
