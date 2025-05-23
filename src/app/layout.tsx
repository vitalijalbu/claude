'use client';
import '@/assets/styles/app.css';
import { usePathname } from 'next/navigation';
import { Toaster } from 'sonner';
import 'dayjs/locale/it';
import dayjs from 'dayjs';
dayjs.locale('it');
import { QueryClientProvider } from '@tanstack/react-query';
import { ReactQueryDevtools } from '@tanstack/react-query-devtools';
import queryClient from '@/lib/react-query-client';
import { Provider } from 'jotai';
import { SiteHeader } from '@/shared/partials/site-header';
import { SiteFooter } from '@/shared/partials/site-footer';

export default function RootLayout({ children }) {
	const pathname = usePathname();

	return (
		<html lang="it">
			<head></head>
			<body>
				<Provider>
					<QueryClientProvider client={queryClient}>
						<ReactQueryDevtools
							initialIsOpen={false}
							position="right"
						/>
						<SiteHeader />
						{children}
						<Toaster
							richColors
							position="top-center"
						/>
						<SiteFooter />
					</QueryClientProvider>
				</Provider>
			</body>
		</html>
	);
}
