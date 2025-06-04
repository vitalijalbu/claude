import {
  Modal,
  ModalContent,
  ModalHeader,
  ModalBody,
  ModalFooter,
  Button,
  useDisclosure,
  Checkbox,
  Input,
  Link,
  Card,
  CardBody,
  Form,
} from "@heroui/react";
import Image from "next/image";

export default function ModalWishlist() {
  const { isOpen, onOpen, onOpenChange } = useDisclosure();

  return (
    <>
      <Card shadow="none" className="border">
        <CardBody>
          <Button variant="light" size="lg" onPress={onOpen}>
            Clicca qui per creare una wishlist
          </Button>
          <Image
            src="/images/icons/icon-gift.svg"
            alt="Wishlist Image"
            width={64}
            height={64}
            className="mx-auto my-4"
          />
        </CardBody>
      </Card>

      <Modal
        isOpen={isOpen}
        placement="top-center"
        size="2xl"
        onOpenChange={onOpenChange}
      >
        <ModalContent>
          {(onClose) => (
            <>
              <ModalHeader className="flex flex-col gap-1">
                Scegli il nome della tua wishlist
              </ModalHeader>
              <ModalBody>
                <Form action="/wishlist/create" method="post">
                  <Input label="Nome wishlist" variant="underlined" />
                  <p className="uppercase mt-4">
                    Scegli il nome della tua wishlist, successivamente potrai
                    aggiungere i prodotti, la lista è condivisibile con chi
                    vuoi!{" "}
                  </p>
                </Form>
              </ModalBody>
              <ModalFooter>
                <Button variant="flat" onPress={onClose}>
                  Chiudi
                </Button>
                <Button color="primary" onPress={onClose}>
                  Salva
                </Button>
              </ModalFooter>
            </>
          )}
        </ModalContent>
      </Modal>
    </>
  );
}
