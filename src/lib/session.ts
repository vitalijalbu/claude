import { setCookie, getCookie, deleteCookie } from "cookies-next";
import { toast } from "sonner";

// Function to get the session (JWT and user data)
const getSession = (req, res) => {
  const userCookie = getCookie("user", { req, res });
  const jwt = getCookie("jwt", { req, res, httpOnly: true });
  let user = null;

  if (userCookie) {
    try {
      user = JSON.parse(userCookie);
    } catch (error) {
      console.error("Error parsing user cookie:", error);
    }
  }

  return { user, jwt: jwt || null };
};

// Function to set the session (JWT and user data)
const setSession = ({ jwt, user }, res) => {
  if (jwt || user) {
    setCookie("user", JSON.stringify(user || {}), {
      path: "/",
      res,
      maxAge: 30 * 24 * 60 * 60,
      secure: true,
      sameSite: "strict",
    });

    if (jwt) {
      setCookie("jwt", jwt, {
        path: "/",
        res,
        maxAge: 30 * 24 * 60 * 60,
        secure: true,
        sameSite: "strict",
      });
    }
  }
};

// Function to remove the session
const removeSession = async (res) => {
  try {
    deleteCookie("user", { path: "/", res });
    deleteCookie("jwt", { path: "/", res });

    if (typeof window !== "undefined") {
      window.location.href = "/";
      toast.success("Disconnesso con successo");
    }
  } catch (error) {
    toast.error("An error occurred");
    console.error("Error removing session:", error);
  }
};

export { getSession, setSession, removeSession };
