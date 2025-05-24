'use client';
import AuthLayout from '@/layouts/auth-layout';
import { Link, useForm } from '@inertiajs/react';
import { IconLock, IconUser } from '@tabler/icons-react';
import { Button, Card, Form, Input, message } from 'antd';
import { useState } from 'react';

export default function Login(props) {


  const [form] = Form.useForm();
  const { data, setData, isDirty, processing, post, errors} = useForm({
    email: '',
    password: '',
  });

  console.log('Login component mounted', errors);

  // Aggiorna lo stato di useForm quando cambia un input
  const handleChange = (changedValues) => {
    setData((prevData) => ({ ...prevData, ...changedValues }));
  };

  const handleSubmit = () => {
    post('login', {
      preserveScroll: true,
      onSuccess: () => {
        // Show success message only if login is successful
        message.success('Accesso effettuato');
      },
      onError: (errors) => {
        // Handle errors and display the error message
        if (errors?.error) {
          message.error(errors.error);  // Show error message
        }
  
        // Set the form fields' error messages if they exist
        form.setFields([
          {
            name: 'email',
            errors: [errors], 
          },
          {
            name: 'password',
            error: [errors], 
          },
        ]);
      },
    });
  };
  
  return (
    <AuthLayout title='Accedi'>
      <Card className='auth-card'>
      <Form
        layout='vertical'
        autoComplete='off'
        form={form}
        onFinish={handleSubmit}
        onValuesChange={handleChange} 
        disabled={processing}
      >
        <Form.Item
          name='email'
          autoComplete='off'
          label='Email'
          rules={[{ required: true, message: 'Il campo è obbligatorio' }]}
          defaultValue={data?.email}
        >
          <Input
            autoComplete='off'
            placeholder='Indirizzo email'
            prefix={<IconUser className='text-slate-400' />}
          />
        </Form.Item>
        <Form.Item
          label='Password'
          name='password'
          rules={[{ required: true, message: 'La password è obbligatoria' }]}
        >
          <Input.Password
            placeholder='Password'
            prefix={<IconLock className='text-slate-400' />}
          />
        </Form.Item>
        <Form.Item>
          <Button
            htmlType='submit'
            aria-label='Login'
            type='primary'
            block
            loading={processing}
            disabled={processing || !isDirty}
          >
            Accedi
          </Button>
        </Form.Item>
      </Form>
      </Card>
    </AuthLayout>
  );
}
