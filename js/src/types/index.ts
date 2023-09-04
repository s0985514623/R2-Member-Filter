export * from './wpRestApi'
export * from './custom'
export * from './dataProvider'

export type TConstant<T> = {
  label: string
  value: T
  color?: string
}
