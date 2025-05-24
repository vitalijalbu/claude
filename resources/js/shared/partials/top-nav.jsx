import React, {useState} from "react";
import { Link, router } from "@inertiajs/react"
import {
  IconAlertCircle,
  IconChevronDown,
  IconChevronLeft,
  IconChevronRight,
  IconLogout,
  IconMenu2,
  IconSettings,
  IconUserCircle
} from "@tabler/icons-react";
import useBreakpoint from "antd/lib/grid/hooks/useBreakpoint";
import { Dropdown, Space, Avatar, Modal, Button, Flex, Divider, Typography } from "antd";
const { Text } = Typography;

const TopNav = ({ openSidebar }, props) => {
  const confirm = Modal.confirm;
  const { auth } = props;
  const breakpoints = useBreakpoint();
  const [isClient, setIsClient] = useState(false);
  const isMobile = !breakpoints.lg;


  /* Logout Action */
  const handleLogout = () => {
    Modal.confirm({
      title: "Are you sure you want to go out?",
      icon: <IconAlertCircle />,
      transitionName: "ant-modal-slide-up",
      content:
        "You are logging out of the OnlyEscort platform, are you sure you want to proceed?",
      okText: "Sign Out",
      okType: "danger",
      cancelText: "Cancel"
      //   onOk() {
      //     logoutAction()
      //       .then((data) => {
      //         message.success("Logged out successfully.");
      //         router.push("/login");
      //       })
      //       .catch((error) => {
      //         console.log(error);
      //         message.error("Error logging out. Please try again.");
      //       });
      //   }
    })
  }

  const items = [
    {
      key: "settings",
      icon: <IconSettings className="text-primary" />,
      label: <Link href="/settings">Impostazioni</Link>,
    }, 
    {
      key: "profile",
      icon: <IconUserCircle className="text-primary" />,
      label: <Link href="/settings/profile">Il mio profilo</Link>,
    },
    {
      type: "divider",
    },
    {
      key: "logout",
      danger: true,
      onClick: () => handleLogout(),
      icon: <IconLogout className="text-red" />,
      label: "Esci",
    }
  ]

  return (
    <Flex
    justify={isMobile ? "space-between" : "flex-end"}
    align="center"
    className="h-full"
  >
    {isMobile && <Button
      type="text"
      icon={<IconMenu2 />}
      onClick={openSidebar}
    />}
    <div className="float-right">
      <Dropdown
        className="min-w-full"
        trigger={["click"]}
        placement="bottomRight"
        menu={{
          items,
        }}
      >
        <div className="flex items-center cursor-pointer h-full">
          <Avatar
            shape="square"
            icon={<IconUserCircle className="text-primary" />}
          />
          <Divider type="vertical" />
          <div className="block">
            <Text className="block w-100">
              demo
            </Text>
          </div>
          <IconChevronDown color="#A1A8B0" />
        </div>
      </Dropdown>
    </div>
  </Flex>
  )
}
export default TopNav;