import { useParams } from 'react-router-dom';
import { Get } from 'components/ApiRequest';
import ReactApexChart from 'react-apexcharts';
import { useState, useEffect } from 'react';
import Links from 'routes/ApiRoutes';

const areaChartOptions = {
    chart: {
        height: 450,
        type: 'line',
        stacked: false,
        toolbar: {
            show: true
        }
    },
    dataLabels: {
        enabled: false
    },
    labels: {
        format: 'MM-DD-HH-MM'
    },
    stroke: {
        curve: 'smooth',
        width: 3
    },
    grid: {
        strokeDashArray: 1
    },
    xaxis: {
        type: 'datetime',
        labels: {
            formatter: function (value, timestamp, opts) {
                return opts.dateFormatter(new Date(timestamp), 'dd MMM HH:mm');
            }
        }
    },
    annotations: {
        yaxis: [
            {
                y: 35,
                borderColor: '#00E396',
                label: {
                    borderColor: '#00E396',
                    style: {
                        color: '#fff',
                        background: '#00E396'
                    },
                    text: 'Support'
                }
            }
        ]
    }
};

const Chart = () => {
    const { id } = useParams();
    const [data, setData] = useState([]);
    const [options, setOptions] = useState(areaChartOptions);
    // let minimum = 20;
    // let maximum = 40;

    useEffect(() => {
        const fetchDeviceData = async () => {
            try {
                const response = await Get(Links(`sensorReadings`, id));

                setData(response['hydra:member']);
            } catch (error) {
                console.error('Error fetching sensor data:', error);
            }
        };

        fetchDeviceData();
    }, [id]);

    // useEffect(() => {
    //     setOptions((prevState) => ({
    //         ...prevState,
    //         annotations: {
    //             yaxis: [
    //                 {
    //                     y: minimum,
    //                     borderColor: '#00E396',
    //                     label: {
    //                         borderColor: '#00E396',
    //                         style: {
    //                             color: '#fff',
    //                             background: '#00E396'
    //                         },
    //                         text: 'minimum'
    //                     }
    //                 },
    //                 {
    //                     y: maximum,
    //                     borderColor: '#00E396',
    //                     label: {
    //                         borderColor: '#00E396',
    //                         style: {
    //                             color: '#fff',
    //                             background: '#00E396'
    //                         },
    //                         text: 'maximum'
    //                     }
    //                 }
    //             ]
    //         }
    //     }));
    // }, [minimum, maximum]);

    return <ReactApexChart options={options} series={data} height={450} />;
};

export default Chart;
