import React, { useState } from 'react'

// import { nanoid } from 'nanoid'

import { Table, Button } from 'antd'
import type { ColumnsType } from 'antd/es/table'
import { columnsSetting } from './TableColumns'
import { mkConfig, generateCsv, download } from 'export-to-csv'

export type UsersDataArray = {
  key?: React.Key
  Username?: string
  Email?: string
  UserID?: Number
  CompletedOders?: Number | 0
  CompletedProducts?: { productName: string; productID: Number }[] | []
  CartProducts?: { productName: string; productID: Number }[] | []
}
const columns: ColumnsType<UsersDataArray> = columnsSetting

const MemberPage: React.FC = () => {
  const usersData: UsersDataArray[] = window?.memderData?.usersDataAarray
  const [
    selectedRowKeys,
    setSelectedRowKeys,
  ] = useState<React.Key[]>([])
  const [
    selectedRowsArray,
    setSelectedRowsArray,
  ] = useState<UsersDataArray[]>([])
  const [
    loading,
    setLoading,
  ] = useState(false)

  const exportCSV = () => {
    setLoading(true)

    // 转换数据，将嵌套的对象转换为适合CSV的字符串

    const transformedData = selectedRowsArray.map((user) => ({
      姓名: user.Username,
      Email: user.Email,
      已完成訂單: user.CompletedOders,
      購買過商品: user.CompletedProducts?.map((product) => product.productName).join(', '),
      購物車未結商品: user.CartProducts?.map((product) => product.productName).join(', '),
    }))
    const csvConfig = mkConfig({
      filename: '會員篩選資料',
      useKeysAsHeaders: true,
    })
    const csv = generateCsv(csvConfig)(transformedData)
    download(csvConfig)(csv)

    setSelectedRowKeys([])
    setLoading(false)
  }

  const onSelectChange = (newSelectedRowKeys: React.Key[], selectedRows: UsersDataArray[]) => {
    setSelectedRowKeys(newSelectedRowKeys)
    setSelectedRowsArray(selectedRows)
  }
  const rowSelection = {
    selectedRowKeys,
    onChange: onSelectChange,
  }
  const hasSelected = selectedRowKeys.length > 0

  return (
    <div className="w-full pr-5">
      <h1>會員篩選</h1>
      <div className="exportMember" style={{ marginBottom: 16 }}>
        <Button type="primary" onClick={exportCSV} disabled={!hasSelected} loading={loading}>
          匯出會員資料
        </Button>
        <span style={{ marginLeft: 8 }}>{hasSelected ? `已選擇 ${selectedRowKeys.length} 筆會員` : ''}</span>
      </div>
      <div className="pr-5">
        <Table rowSelection={rowSelection} dataSource={usersData} columns={columns} />
      </div>
    </div>
  )
}

export default MemberPage
