import React, { useState } from 'react';
import { Collapse } from 'antd';
import { DashboardOutlined } from '@ant-design/icons';

const { Panel } = Collapse;

const DashboardGroup: React.FC = () => {
    const [activeKey, setActiveKey] = useState<string | string[]>(['dashboard']);

    const handlePanelChange = (key: string | string[]) => {
        setActiveKey(key);
    };

    return (
        <Collapse activeKey={activeKey} onChange={handlePanelChange}>
            <Panel header="Navigation" key="group-dashboard">
                <Panel header="Dashboardo" key="dashboard">
                    {/* You can put your content for Dashboardo here */}
                </Panel>
            </Panel>
        </Collapse>
    );
};

export default DashboardGroup;
