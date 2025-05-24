import React, { useState } from 'react';
import { Layout, Drawer } from 'antd';
const { Header, Sider, Content } = Layout;
import SideNav from '@/shared/partials/side-nav';
import TopNav from '@/shared/partials/top-nav';
import useBreakpoint from "antd/lib/grid/hooks/useBreakpoint";
import clsx from 'clsx';

export default function DashboardLayout({ children }) {
  const [isSidebarOpen, setSidebarOpen] = useState(false);
  const breakpoints = useBreakpoint();

  const isMobile = !breakpoints.lg;

  const handleToggleSidebar = () => {
    setSidebarOpen((prev) => !prev);
  };

  return (
    <Layout
      hasSider={!isMobile}
      className={clsx("h-lvh", {
        "sidebar-opened": isSidebarOpen,
        "mobile-sidebar": isMobile,
      })}
    >
      {/* Sidebar or Drawer */}
      {isMobile ? (
        <Drawer
          title="Menu"
          placement="left"
          closable
          onClose={() => setSidebarOpen(false)}
          open={isSidebarOpen}
          width="80%"
        >
          <SideNav onLinkClick={() => setSidebarOpen(false)} />
        </Drawer>
      ) : (
        <Sider
          width={256}
          collapsedWidth={0}
          collapsed={false}
          className="border-r border-gray-200 bg-white"
          onCollapse={(collapsed) => setSidebarOpen(!collapsed)}
        >
          <SideNav />
        </Sider>
      )}

      {/* Main Layout */}
      <Layout>
        {/* Header */}
        <Header theme="light" className="border-b border-gray-200">
          <TopNav openSidebar={handleToggleSidebar} />
        </Header>

        {/* Content */}
        <Content className="h-full overflow-y-scroll overflow-x-hidden py-4 bg-gray-50">
          <div className="container">{children}</div>
        </Content>
      </Layout>
    </Layout>
  );
}
