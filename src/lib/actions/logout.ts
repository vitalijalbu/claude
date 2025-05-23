import { IconLogout } from "@/assets/icons";
import confirm from "@/components/confirm";
import { removeSession } from "@/lib/session";

export const handleLogout = async () => {
  return confirm({
    title: "Sei sicuro di voler effettuare il logout?",
    message: "Stai uscendo dall'app, sei sicuro di voler procedere?",
    cancelText: "Annulla",
    confirmText: "Esci",
    isDanger: true,
    icon: <IconLogout color="#fff" />,
    async onOk() {
      await removeSession();
    },
  });
};
