import React, { useState } from 'react';
import { TextField, Typography, Grid } from '@mui/material';
import { styled, SystemProps } from '@mui/system';
import { Put } from 'components/ApiRequest';

const Container = styled('div')({
    display: 'flex',
    alignItems: 'center',
    gap: '8px'
});

export interface EditableTextFieldProps {
    value?: string;
    nullable?: boolean;
    maxLength?: bigint;
    url: string;
    property: string;
}

const EditableTextField: React.FC<EditableTextFieldProps> = ({ value = null, nullable = false, maxLength = 30, url, property }) => {
    const [editedField, setEditedField] = useState(value || '');
    const [fieldError, setFieldError] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [errorMessage, setErrorMessage] = useState('');

    const handleFieldClick = () => {
        setIsEditing(true);
    };

    const handleFieldChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const { value } = event.target;
        setEditedField(value);
        setFieldError(false); // Reset the error when the value changes
    };

    const handleFieldBlur = () => {
        if (!nullable && editedField.trim() === '') {
            setFieldError(true);
            setErrorMessage('Field must not be empty');
        } else if (editedField.length > maxLength) {
            setFieldError(true);
            setErrorMessage('Field should have up to ' + { maxLength } + 'characters');
        } else {
            setIsEditing(false);
            const payload = { [property]: editedField };
            Put(url, payload);
        }
    };

    return (
        <Grid>
            {isEditing ? (
                <TextField
                    value={editedField}
                    onChange={handleFieldChange}
                    onBlur={handleFieldBlur}
                    autoFocus
                    error={fieldError} // Set the error state
                    helperText={fieldError && errorMessage} // Display error message
                />
            ) : (
                <Container onClick={handleFieldClick}>{editedField}</Container>
            )}
        </Grid>
    );
};

export default EditableTextField;
