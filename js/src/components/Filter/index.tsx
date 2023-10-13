import React from 'react'
import { Collapse, Form, Select, Input, Button } from 'antd'
import { nanoid } from 'nanoid'

const { Option } = Select
const index: React.FC<{ onFilter: (values: any) => void }> = ({ onFilter }) => {
    const CompletedProducts = window?.productData?.productArray || []
    const CartProducts = window?.productData?.productArray || []
    const [form] = Form.useForm()
    const handleOnFinish = (values: any) => {
        onFilter(values)
    }

    const children = (
        <Form onFinish={handleOnFinish} layout="vertical" form={form}>
            <div className="grid grid-cols-2 gap-6">
                <Form.Item label="顯示名稱" name="userName">
                    <Input className="w-full" allowClear />
                </Form.Item>
                <Form.Item label="Email" name="userEmail">
                    <Input className="w-full" allowClear />
                </Form.Item>
                <Form.Item label="購買過商品" name={['completedProducts']}>
                    <Select mode="multiple" placeholder="選擇商品">
                        {CompletedProducts.map((CompletedProduct) => (
                            <Option key={nanoid()} value={CompletedProduct.productName}>
                                {CompletedProduct.productName}
                            </Option>
                        ))}
                    </Select>
                </Form.Item>
                <Form.Item label="購物車未結商品" name="cartProducts">
                    <Select mode="multiple" placeholder="選擇商品">
                        {CartProducts.map((CartProduct) => (
                            <Option key={nanoid()} value={CartProduct.productName}>
                                {CartProduct.productName}
                            </Option>
                        ))}
                    </Select>
                </Form.Item>
            </div>
            <Form.Item className="mt-6">
                <Button type="primary" htmlType="submit" className="w-full">
                    Filter
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
                    label: <span className="font-semibold text-base relative -top-0.5">會員篩選</span>,
                    children,
                },
            ]}
        />
    )
}

export default index
