import type { ColumnsType } from 'antd/es/table'
import type { UsersDataArray } from './index'
import { Space, Tag } from 'antd'

//TODO 這個HOOK怎麼用的?
// import useConstantSelect from 'hooks/useConstantSelect'

//TODO 這個是什麼?

declare global {
    interface Window {
        productData?: {
            productArray: [{ productName: string }]
        }
    }
}

export const columnsSetting: ColumnsType<UsersDataArray> = [
    {
        title: '顯示名稱',
        dataIndex: 'userName',
        key: 'userName',
        width: '20%',
        render: (text, record) => (
            <a target="_Blank" href={`${window?.appData?.siteUrl}/wp-admin/edit.php?s&post_status=all&post_type=shop_order&action=-1&m=0&_customer_user=${record.userID}`} rel="noreferrer">
                {text}
            </a>
        ),
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
        width: '20%',
    },

    // {
    //   title: '使用者ID',
    //   dataIndex: 'UserID',
    //   key: 'UserID',
    // },

    {
        title: '已完成訂單',
        dataIndex: 'completedOrders',
        key: 'completedOrders',
        width: '20%',
        sorter: (a, b) => (a.completedOrders as number) - (b.completedOrders as number),
        render: (text, record) => (
            <a target="_Blank" href={`${window?.appData?.siteUrl}/wp-admin/edit.php?s&post_status=all&post_type=shop_order&action=-1&m=0&_customer_user=${record.userID}`} rel="noreferrer">
                {text}
            </a>
        ),
    },
    {
        title: '購買過商品',
        dataIndex: 'completedProducts',
        key: 'completedProducts',
        width: '20%',
        render: (_, { completedProducts }) => (
            <Space size="small" wrap>
                {completedProducts?.map((completedProduct) => {
                    const productName = completedProduct.productName
                    const productID = completedProduct.productID as number
                    return <Tag key={productID}>{productName}</Tag>
                })}
            </Space>
        ),
    },
    {
        title: '購物車未結商品',
        dataIndex: 'cartProducts',
        key: 'cartProducts',
        width: '20%',
        render: (_, { cartProducts }) => (
            <Space size="small" wrap>
                {cartProducts?.map((cartProduct) => {
                    const productName = cartProduct.productName
                    const productID = cartProduct.productID as number
                    return <Tag key={productID}>{productName}</Tag>
                })}
            </Space>
        ),
    },
]
