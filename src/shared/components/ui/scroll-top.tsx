'use client';
import { useEffect, useState } from 'react';
import { Button } from './button';
import { ArrowUpToLine } from 'lucide-react';

export function ScrollToTop() {
	const [visible, setVisible] = useState(false);

	useEffect(() => {
		const onScroll = () => {
			// Set visible when scroll position is greater than 0
			setVisible(document.documentElement.scrollTop > 0);
		};

		// Initial check to hide the button when at the top
		onScroll();

		document.addEventListener('scroll', onScroll);

		// Cleanup event listener on component unmount
		return () => document.removeEventListener('scroll', onScroll);
	}, []);

	return (
		<>
			{visible && (
				<Button
					variant="outline"
					className="fixed right-4 bottom-4"
					size="icon"
					onClick={() =>
						window.scrollTo({
							top: 0,
							behavior: 'smooth',
						})
					}>
					<ArrowUpToLine />
				</Button>
			)}
		</>
	);
}
