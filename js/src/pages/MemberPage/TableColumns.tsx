import type { ColumnsType } from 'antd/es/table'
import type { UsersDataArray } from './index'
import { Space, Tag, Button, Select } from 'antd'

//TODO 這個HOOK怎麼用的?
// import useConstantSelect from 'hooks/useConstantSelect'

//TODO 這個是什麼?

declare global {
  interface Window {
    ProductData?: {
      ProductArray: [{ productName: string }]
    }
  }
}

const { Option } = Select
export const columnsSetting: ColumnsType<UsersDataArray> = [
  {
    title: '顯示名稱',
    dataIndex: 'Username',
    key: 'Username',
    width: '20%',
  },
  {
    title: 'Email',
    dataIndex: 'Email',
    key: 'Email',
    width: '20%',
  },

  // {
  //   title: '使用者ID',
  //   dataIndex: 'UserID',
  //   key: 'UserID',
  // },

  {
    title: '已完成訂單',
    dataIndex: 'CompletedOders',
    key: 'CompletedOders',
    width: '20%',
    sorter: (a, b) =>
      (a.CompletedOders as number) - (b.CompletedOders as number),
  },

  {
    title: '購買過商品',
    dataIndex: 'CompletedProducts',
    key: 'CompletedProducts',
    width: '20%',
    render: (_, { CompletedProducts }) => (
      <Space size="small" wrap>
        {CompletedProducts?.map((CompletedProduct) => {
          const productName = CompletedProduct.productName
          const productID = CompletedProduct.productID as number
          return <Tag key={productID}>{productName}</Tag>
        })}
      </Space>
    ),
    filterDropdown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters,
    }) => {
      const CompletedProducts = window?.ProductData?.ProductArray || []
      return (
        <div style={{ padding: 8 }}>
          <Select
            mode="multiple"
            placeholder="選擇商品"
            value={selectedKeys}
            onChange={(values) => setSelectedKeys(values)}
            style={{ width: 188, marginBottom: 8, display: 'block' }}
          >
            {CompletedProducts.map((CompletedProduct) => (
              <Option key={CompletedProduct.productName}>
                {CompletedProduct.productName}
              </Option>
            ))}
          </Select>
          <Button
            type="primary"
            onClick={() => confirm()}
            size="small"
            style={{ width: 90, marginRight: 8 }}
          >
            确定
          </Button>
          <Button
            onClick={() => clearFilters()} //TODO 錯誤在哪邊?
            size="small"
            style={{ width: 90 }}
          >
            重置
          </Button>
        </div>
      )
    },

    onFilter: (value, record) => {
      const completedProducts = record.CompletedProducts || []
      return completedProducts.some((product) =>
        product.productName.includes(value as string),
      )
    },
  },
  {
    title: '購物車未結商品',
    dataIndex: 'CartProducts',
    key: 'CartProducts',
    width: '20%',
    render: (_, { CartProducts }) => (
      <Space size="small" wrap>
        {CartProducts?.map((CartProduct) => {
          const productName = CartProduct.productName
          const productID = CartProduct.productID as number
          return <Tag key={productID}>{productName}</Tag>
        })}
      </Space>
    ),
    filterDropdown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters,
    }) => {
      const CompletedProducts = window?.ProductData?.ProductArray || []
      return (
        <div style={{ padding: 8 }}>
          <Select
            mode="multiple"
            placeholder="選擇商品"
            value={selectedKeys}
            onChange={(values) => setSelectedKeys(values)}
            style={{ width: 188, marginBottom: 8, display: 'block' }}
          >
            {CompletedProducts.map((CompletedProduct) => (
              <Option key={CompletedProduct.productName}>
                {CompletedProduct.productName}
              </Option>
            ))}
          </Select>
          <Button
            type="primary"
            onClick={() => confirm()}
            size="small"
            style={{ width: 90, marginRight: 8 }}
          >
            确定
          </Button>
          <Button
            onClick={() => clearFilters()}
            size="small"
            style={{ width: 90 }}
          >
            重置
          </Button>
        </div>
      )
    },
    onFilter: (value, record) => {
      const completedProducts = record.CompletedProducts || []
      return completedProducts.some((product) =>
        product.productName.includes(value as string),
      )
    },
  },
]
