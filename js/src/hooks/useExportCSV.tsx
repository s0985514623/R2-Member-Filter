import { useState } from 'react'
import { mkConfig, generateCsv, download } from 'export-to-csv'
import { UsersDataArray } from 'pages/MemberPage'

export const exportCSV = () => {
  const [
    loading,
    setLoading,
  ] = useState(false)
  const handleExportCSV = (selectedRowsArray: UsersDataArray[]) => () => {
    setLoading(true)

    // 转换数据，将嵌套的对象转换为适合CSV的字符串

    const transformedData = selectedRowsArray.map((user) => ({
      姓名: user.userName,
      Email: user.email,
      已完成訂單: user.completedOrders,
      購買過商品: user.completedProducts?.map((product) => product.productName).join(', '),
      購物車未結商品: user.cartProducts?.map((product) => product.productName).join(', '),
    }))
    const csvConfig = mkConfig({
      filename: '會員篩選資料',
      useKeysAsHeaders: true,
    })
    const csv = generateCsv(csvConfig)(transformedData)
    download(csvConfig)(csv)

    setLoading(false)
  }
  return { loading, handleExportCSV }
}
