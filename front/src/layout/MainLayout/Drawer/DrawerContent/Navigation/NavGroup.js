import PropTypes from 'prop-types';
import { useState } from 'react';
import { useSelector } from 'react-redux';

// material-ui
import { Box, List, Typography, Accordion, AccordionSummary, AccordionDetails, styled, AccordionProps } from '@mui/material';
import { ExpandMore } from '@mui/icons-material';

// project import
import NavItem from './NavItem';

// ==============================|| NAVIGATION - LIST GROUP ||============================== //

const NavGroup = ({ item }) => {
    const menu = useSelector((state) => state.menu);
    const { drawerOpen } = menu;
    const [expanded, setExpanded] = useState(null);

    const handleAccordionChange = (panel) => (event, isExpanded) => {
        setExpanded(isExpanded ? panel : null);
    };

    const navCollapse = item.children?.map((menuItem) => {
        switch (menuItem.type) {
            case 'collapse':
                menuItem.color = 'transparent';
                return (
                    <Accordion
                        key={menuItem.id}
                        expanded={expanded === menuItem.id}
                        onChange={handleAccordionChange(menuItem.id)}
                        sx={{ boxShadow: 'none' }}
                        disableGutters={true}
                    >
                        <AccordionSummary
                            expandIcon={<ExpandMore />}
                            aria-controls={`${menuItem.id}-content`}
                            id={`${menuItem.id}-header`}
                            sx={{
                                padding: 0,
                                paddingRight: 1,
                                '&:not(:last-child)': {
                                    borderBottom: 0
                                },
                                '&:before': {
                                    display: 'none'
                                }
                            }}
                        >
                            <NavItem key={menuItem.id} item={menuItem} level={1} />
                        </AccordionSummary>
                        <AccordionDetails sx={{ padding: 0 }}>
                            <List disablePadding>
                                <NavGroup item={menuItem} />
                            </List>
                        </AccordionDetails>
                    </Accordion>
                );
            case 'item':
                return <NavItem key={menuItem.id} item={menuItem} level={1} />;
            default:
                return (
                    <Typography key={menuItem.id} variant="h6" color="error" align="center">
                        Fix - Group, Collapse, or Items
                    </Typography>
                );
        }
    });

    return (
        <List
            subheader={
                item.type !== 'collapse' &&
                item.title &&
                drawerOpen && (
                    <Box sx={{ pl: 3, mb: 1.5 }}>
                        <Typography variant="subtitle2" color="textSecondary">
                            {item.title}
                        </Typography>
                    </Box>
                )
            }
            sx={{ mb: drawerOpen ? 1.5 : 0, py: 0, zIndex: 0 }}
        >
            {navCollapse}
        </List>
    );
};

NavGroup.propTypes = {
    item: PropTypes.object
};

export default NavGroup;
