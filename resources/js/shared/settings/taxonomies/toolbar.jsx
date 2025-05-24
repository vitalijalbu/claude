import React, { useState } from "react";
import { Menu } from 'antd';
import { Link } from "@inertiajs/react";

const Toolbar = ({data}) => {

  return (
    <Menu mode="horizontal" items={[
      { label: <Link href="/taxonomies">Tags</Link>, key: "/taxonomies" },
      { label: <Link href="/settings/taxonomy-groups">Gruppi</Link>, key: "/settings/taxonomy-groups" },
    ]}/>
  );
};


export default Toolbar;