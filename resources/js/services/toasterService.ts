import { useToast } from "@/components/ui/toast";

const { toast } = useToast()

export function toastSuccess(message: string) {
    toast({ title: message, variant: 'default' });
}

export function toastError(message: string) {
    toast({ title: message, variant: 'destructive' });
}
