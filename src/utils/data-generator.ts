export function generateRandomPassword(length = 6) {
  const lowercase = "abcdefghijklmnopqrstuvwxyz";
  const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  const numbers = "0123456789";
  //const specialChars = '!@#$%^&*()_+[]{}|;:,.<>?';

  const allChars = lowercase + uppercase + numbers;

  let password = "";
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * allChars.length);
    password += allChars[randomIndex];
  }

  return password;
}

export function generateUsername(lastId) {  
  const randomNumber = Math.floor(Math.random() * 100); 
  
  return `pt-${lastId + 1 + randomNumber}`;
}