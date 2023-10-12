import React, { useState } from 'react'
import { Table, Button } from 'antd'
import type { ColumnsType } from 'antd/es/table'
import { columnsSetting } from './TableColumns'
import Filter from 'components/Filter'
import { exportCSV } from 'hooks/useExportCSV'

export type UsersDataArray = {
  key?: React.Key
  userName?: string
  email?: string
  userID?: Number
  completedOrders?: Number | 0
  completedProducts?: { productName: string; productID: Number }[]
  cartProducts?: { productName: string; productID: Number }[]
}
const columns: ColumnsType<UsersDataArray> = columnsSetting

const MemberPage: React.FC = () => {
  const usersData: UsersDataArray[] = window?.memberData?.usersDataArray
  const [
    fxnUsersData,
    setFxnUsersData,
  ] = useState(usersData)
  const [
    selectedRowKeys,
    setSelectedRowKeys,
  ] = useState<React.Key[]>([])
  const [
    selectedRowsArray,
    setSelectedRowsArray,
  ] = useState<UsersDataArray[]>([])

  //匯出CSV處理

  const { loading, handleExportCSV } = exportCSV()

  //處理Table選擇

  const onSelectChange = (newSelectedRowKeys: React.Key[], selectedRows: UsersDataArray[]) => {
    setSelectedRowKeys(newSelectedRowKeys)
    setSelectedRowsArray(selectedRows)
  }
  const rowSelection = {
    selectedRowKeys,
    onChange: onSelectChange,
  }
  const hasSelected = selectedRowKeys.length > 0

  //處理Filter返回的資料

  const handleFilterChange = (newFilter: any) => {
    // setFilter(newFilter) // 更新父组件的筛选条件
    // console.log(newFilter)

    const filterUser = newFilter?.userName
    const filterEmail = newFilter?.userEmail
    const completedProducts = newFilter?.completedProducts
    const cartProducts = newFilter?.cartProducts

    const filterData = usersData.filter((user) => {
      if (filterUser !== undefined && filterUser !== '' && filterUser !== user.userName) {
        return false
      }
      if (filterEmail !== undefined && filterEmail !== '' && filterEmail !== user.email) {
        return false
      }
      if (completedProducts !== undefined && completedProducts.length !== 0) {
        return user.completedProducts?.some((filterProduct) => {
          return completedProducts.some((product: any) => product === filterProduct.productName)
        })
      }
      if (cartProducts !== undefined && cartProducts.length !== 0) {
        return user.cartProducts?.some((filterProduct) => {
          return cartProducts.some((product: any) => product === filterProduct.productName)
        })
      }
      return true
    })
    setFxnUsersData(filterData)
  }

  return (
    <div className="w-full pr-5">
      <h1>會員篩選</h1>
      <div className="exportMember" style={{ marginBottom: 16 }}>
        <Button type="primary" onClick={handleExportCSV(selectedRowsArray)} disabled={!hasSelected} loading={loading}>
          匯出會員資料
        </Button>
        <span style={{ marginLeft: 8 }}>{hasSelected ? `已選擇 ${selectedRowKeys.length} 筆會員` : ''}</span>
      </div>
      <div className="pr-5 flex flex-col gap-10">
        <Filter onFilter={handleFilterChange} />
        <Table rowSelection={rowSelection} dataSource={fxnUsersData} columns={columns} />
      </div>
    </div>
  )
}

export default MemberPage
