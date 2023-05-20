import { useEffect, useState } from 'react';
import { useParams, Link as RouterLink } from 'react-router-dom';
import { Chip, Grid, Typography } from '@mui/material';
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

const DeviceListView = () => {
    const { id } = useParams();
    const [devices, setDevices] = useState([]);

    useEffect(() => {
        const config = {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('jwt_token')
            }
        };

        const fetchDevicesData = async () => {
            try {
                const response = await axios.get(Links('devicesFullList'), config);
                setDevices(response.data['hydra:member']);
            } catch (error) {
                console.error('Error fetching devices data:', error);
            }
        };

        fetchDevicesData();
    }, [id]);

    if (devices.length === 0) {
        return <div>Loading...</div>; // Placeholder for when data is loading
    }

    return (
        <Grid container spacing={2}>
            {devices.map((device) => (
                <Grid item key={device.id} xs={12} sm={6} md={4}>
                    <RouterLink to={`/device/${device.id}`} style={{ textDecoration: 'none' }}>
                        <MainCard>
                            <CardContent>
                                <TitleContainer>
                                    <Title>{device.name}</Title>
                                    <Chip
                                        color={device.active ? 'success' : 'error'}
                                        icon={<Icons.PoweroffOutlined style={{ fontSize: '1rem', color: 'success' }} />}
                                        sx={{ ml: 1.25, pl: 1 }}
                                        size="small"
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
                                </DeviceInfo>
                            </CardContent>
                        </MainCard>
                    </RouterLink>
                </Grid>
            ))}
        </Grid>
    );
};

export default DeviceListView;
