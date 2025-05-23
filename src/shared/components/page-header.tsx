'use client';

import React from 'react';
import { Separator } from '@/components/index';

type PageHeaderProps = {
	title: string;
	subtitle?: string;
	separator?: boolean;
	className?: string;
	extra?: React.ReactNode;
};

export function PageHeader({ title, subtitle, extra, className, separator = true }: PageHeaderProps) {
	return (
		<section className={className}>
			<div className="flex items-start justify-between flex-wrap gap-4 mb-6">
				<div>
					<h1 className="text-3xl font-bold tracking-tight">{title}</h1>
					{subtitle && <p className="text-muted-foreground mt-1 text-md">{subtitle}</p>}
				</div>
				{extra && <div className="flex items-center gap-2">{extra}</div>}
				{separator && <Separator className="mb-4" />}
			</div>
		</section>
	);
}
