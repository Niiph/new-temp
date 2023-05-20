import { useEffect, useState } from 'react';
import { Link as RouterLink, useParams } from 'react-router-dom';
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

const DeviceView = () => {
    const { id } = useParams();
    const [device, setDevice] = useState(null);

    useEffect(() => {
        const config = {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('jwt_token')
            }
        };

        const fetchDeviceData = async () => {
            try {
                const response = await axios.get(Links(`devices`) + id, config);
                setDevice(response.data);
            } catch (error) {
                console.error('Error fetching device data:', error);
            }
        };

        fetchDeviceData();
    }, [id]);

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
                                    <Chip
                                        color={sensor.active ? 'success' : 'error'}
                                        icon={<Icons.PoweroffOutlined style={{ fontSize: '1rem', color: 'success' }} />}
                                        sx={{ ml: 1.25, pl: 1 }}
                                        size="small"
                                    />
                                </TitleContainer>
                                {/*<DeviceInfo>*/}
                                {/*    <Typography variant="body1">*/}
                                {/*        <strong>Short ID:</strong>*/}
                                {/*    </Typography>*/}
                                {/*    <Typography variant="body1">{sensor.shortId}</Typography>*/}
                                {/*</DeviceInfo>*/}
                                {/*<DeviceInfo>*/}
                                {/*    <Typography variant="body1">*/}
                                {/*        <strong>Password:</strong>*/}
                                {/*    </Typography>*/}
                                {/*    <Typography variant="body1">{sensor.password}</Typography>*/}
                                {/*</DeviceInfo>*/}
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
            <Typography variant="h4">
                <br />
                <strong>Sensors:</strong>
            </Typography>
            {sensors}
        </Grid>
    );
};

export default DeviceView;
