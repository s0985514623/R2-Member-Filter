import { useAjax } from '@/hooks'
import { ajaxNonce } from '@/utils'
import { FormInstance } from 'antd/lib/form'

type TProps = {
    subject: string
    userID?: string
    date: string
    content: string
    userEmail: string[]
    template: string
    form: FormInstance
}

export const useSendMail = () => {
    const mutation = useAjax()
    const { mutate } = mutation
    const sendMail = (props: TProps) => {
        mutate(
            {
                action: 'handle_set_cron_email',
                nonce: ajaxNonce,
                subject: props.subject,
                date: props.date,
                content: props.content,
                userEmail: props.userEmail,
                template: props.template,
            },
            {
                onSuccess: (data) => {
                    console.log(data)
                    props.form.resetFields()
                },
                onError: (error) => {
                    console.log(error)
                },
            },
        )
    }

    return {
        ...mutation,
        sendMail,
    }
}
