import React, { useEffect, useState, useRef } from 'react'
import { Collapse, Form, Button, DatePicker, Select, Input } from 'antd'
import { Editor } from '@tinymce/tinymce-react'
import { nanoid } from 'nanoid'
import { UsersDataArray } from 'pages/MemberPage'
import { useSendMail } from '@/hooks'

const { Option } = Select
const index: React.FC<{ selectedRowsArray: UsersDataArray[] }> = ({ selectedRowsArray }) => {
    const [
        editorValue,
        setEditorValue,
    ] = useState('')
    const editorRef = useRef(null)
    const [form] = Form.useForm()
    const { sendMail, isLoading } = useSendMail()
    const handleOnFinish = async (values: any) => {
        const subject = values?.emailSubject
        const date = values?.sendDate?.format('YYYY-MM-DD HH:mm:ss')
        const content = values?.sendContent?.level?.content
        const content2 = editorRef?.current
        const userEmail = values?.userEmail
        const template = values?.template
        const data = {
            subject,
            date,
            content,
            content2,
            userEmail,
            template,
            form,
        }

        // console.log('🚀 ~ data:', data)

        //呼叫AJAX寄信

        await sendMail(data)
    }

    useEffect(() => {
        //動態賦予userEmail

        form.setFieldsValue({ userEmail: selectedRowsArray.map((user) => user.email) })
        form.setFieldsValue({ userID: selectedRowsArray.map((user) => user.userID) })
    }, [selectedRowsArray])

    const children = (
        <Form onFinish={handleOnFinish} layout="vertical" form={form}>
            <div className="grid grid-cols-1 gap-2">
                <Form.Item hidden name="userID" />
                <Form.Item label="信件標題" name="emailSubject" rules={[{ required: true, message: '請輸入標題' }]}>
                    <Input className="w-full" allowClear />
                </Form.Item>
                <Form.Item label="已選擇會員" name="userEmail" rules={[{ required: true, message: '請選擇會員' }]}>
                    <Select mode="multiple" placeholder="已選擇會員" maxTagCount="responsive">
                        {selectedRowsArray.map((user) => (
                            <Option key={nanoid()} value={user.email}>
                                {user.email}
                            </Option>
                        ))}
                    </Select>
                </Form.Item>
                <Form.Item label="選擇寄送時間" name="sendDate" rules={[{ required: true, message: '請選擇時間' }]}>
                    <DatePicker showTime placeholder="選擇時間" />
                </Form.Item>
                <Form.Item label="選擇信件範本" name="template" rules={[{ required: true, message: '請選擇範本' }]}>
                    <Select
                        placeholder="請選擇信件範本"
                        options={[
                            { value: 'courses_info', label: 'courses_info' },
                            { value: 'template1', label: 'template1' },
                            { value: 'template2', label: 'template2' },
                        ]}
                    />
                </Form.Item>
                <Form.Item label="發送內容" name="sendContent">
                    <Editor
                        ref={editorRef}
                        apiKey={import.meta.env.VITE_TINY_KEY}
                        value={editorValue}
                        onEditorChange={(newValue, _editor) => {
                            setEditorValue(newValue)
                        }}
                        init={{
                            menubar: false,
                            toolbar: 'undo redo | formatSelect | ' + 'bold italic forecolor backcolor | alignleft aligncenter ' + 'alignright alignjustify | bullist numlist outdent indent | ' + 'removeformat | help',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                        }}
                    />
                </Form.Item>
            </div>
            <Form.Item className="mt-6">
                <Button type="primary" htmlType="submit" className="w-full" loading={isLoading}>
                    排程寄信
                </Button>
            </Form.Item>
        </Form>
    )

    return (
        <Collapse
            bordered={false}
            className="bg-white"
            items={[
                {
                    key: 'filters',
                    label: <span className="font-semibold text-base relative -top-0.5">手動寄信</span>,
                    children,
                },
            ]}
        />
    )
}

export default index
