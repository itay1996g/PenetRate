'''
authors :
Etai Yaffe 313515462
Itay Gamliel 207332990
Example of USAGE:

#Stage 1 
#domain='google.com'
#known_subdomains = ['aaa','bbb','bbb','ccc']
#a = SmartSubdomainGuesser(known_subdomains, 1000,domain)
#tt = a.guess()
#print (tt)
#print(len(tt))

#Stage 2 
#tt = a.guess(domain='google.com')
#print (tt)
#print(len(tt))

#Stage3 
#known_subdomains = ['publishers','brawlstars','abb','794','italy','cambio','ink','reseau','townnews-staging','perch','brad','translate']
#a = SmartSubdomainGuesser(known_subdomains, 1000 ,domain)
#tt = a.guess()
#print (tt) 
#print(len(tt))


'''

class SmartSubdomainGuesser(object):
    _COMMON_SUBDOMAINS = ['mobile','info','mobile','media','help','admin','administrator','user','users','register','login','app','api','support','account','he', 'en','alpha', 'beta', 'dev', 'stage', 'test', 'prod', 'staging', 'pre', 'production', 'development', 'testing', 'www', 'backup', 'ww1']
    _LIST_PREFIX = ['alpha', 'beta', 'dev', 'stage', 'test', 'prod', 'staging', 'pre', 'production', 'development', 'testing', 'www', 'backup', 'ww1']
    _SUBDOMAINS_FILE = "subdomains.txt"
    _TOP_10000_SUBDOMAINS_FILE = "top_10000_subdomains.txt"
    _DEF_OUTPUT_LEN = 200


    def __init__(self, known_subdomains, output_len=_DEF_OUTPUT_LEN,domain=None):
        if type(known_subdomains) is not list or len(known_subdomains) == 0:
            raise ValueError("You must provide a list of subdomains")

        self._known_subdomains = known_subdomains
        self._domain = domain
        self._output_len = output_len
        self.known_subdomains_new = []
        self._continue_to_add_flag = True


    def add_new_subdomains(self, new_domain):
        try:
            if len(self.known_subdomains_new) < self._output_len:
                if new_domain not in self._known_subdomains and new_domain not in self.known_subdomains_new:
                    self.known_subdomains_new.append(new_domain)
            else:
                self._continue_to_add_flag = False
        except:
            print('Error in add_new_subdomains')

    def add_prefix(self, domain):
        try:
            for prefix in (self._LIST_PREFIX):
                new_domain = prefix + '.' + domain
                self.add_new_subdomains(new_domain)
                new_domain = prefix + '-' + domain
                self.add_new_subdomains(new_domain)
        except:
            print('Error in add_prefix')


    def add_common_subdomains_and_prefixes(self):
        try:
            #ADD MOST common subdomains
            for new_domain in (self._COMMON_SUBDOMAINS):
                self.add_new_subdomains(new_domain)

            # ADD common PREFIXS on the initial knows subdomains
            for domain in (self._known_subdomains):
                self.add_prefix(domain)

        except:
            print('Error in add_common_subdomains_and_prefixes')


    #Add subdomains that are similar to a given subdomain from subdomains text file
    def add_subdomain_from_same_lines_file(self, subdomain, file_lines):
        try:
            #For each Line in file
            for line in file_lines:
                words = line.split(",")
                #For each word
                for word in words:
                    #Check if one of the words are in knows subdomain
                    if word in subdomain:
                        # Add all words from the line to known_subdomains_new
                        for rest_words in words:
                            # Add all words from the line to known_subdomains_new
                            self.add_new_subdomains(rest_words)
                        break
        except:
            print('Error in add_subdomain_from_same_lines_file')

    
    # Add all words from the line (similar) with PREFIX to known_subdomains_new
    def add_subdomain_from_same_lines_file_with_prefix(self, subdomain, file_lines):
        try:
            #For each Line in file
            for line in file_lines:
                words = line.split(",")
                #For each word
                for word in words:
                    #Check if one of the words are in knows subdomain
                    if word in subdomain:
                        # Add all words from the line (similar) with PREFIX to known_subdomains_new
                        for rest_words in words:
                            self.add_prefix(rest_words)

                        break
        except:
            print('Error in add_subdomain_from_same_lines_file_with_prefix')



    #Add all subdomains from subdomains text file
    def add_all_subdomains_from_file(self, file_lines):
        try:
            #For each Line in file
            for line in file_lines:
                words = line.split(",")
                #For each word
                for word in words:
                    # Add all words from the line to known_subdomains_new
                    self.add_new_subdomains(word)
        except:
            print('Error in add_all_subdomains_from_file')



    #Add all subdomains from subdomains text file with MOST common PREFIXS to them
    def add_all_subdomains_from_file_with_prefix(self, file_lines):
        try:
            #For each Line in file
            for line in file_lines:
                words = line.split(",")
                #For each word
                for word in words:
                    #Add all subdomains from subdomains text file with MOST common PREFIXS to them
                    self.add_prefix(word)

        except:
            print('Error in add_all_subdomains_from_file_with_prefix')


    def add_common_subdomains_and_prefixes_with_digits(self):
        try:
            #ADD MOST common subdomains
            for new_domain in (self._COMMON_SUBDOMAINS):
                for j in range(0, 4):
                    self.add_new_subdomains(new_domain + '-' + str(j))

            # ADD common PREFIXS on the initial knows subdomains
            for domain in (self._known_subdomains):
                for j in range(0, 4):
                    self.add_prefix(domain + '-' + str(j))

        except:
            print('Error in add_common_subdomains_and_prefixes_with_digits')


    #Add Digits 
    def add_digits(self):
        try:
            for i in range(0, 9):
                for domain in self._known_subdomains:
                    if (str(i) in domain):
                        #Add only digits from 0-4
                        for j in range(0, 4):
                            self.add_new_subdomains(domain[:-1]+''+str(j))
        except:
            print('Error in add_digits')



    #Add all subdomains from top_10000_subdomains text file
    def add_all_top_10000_subdomains_from_file(self, file_lines):
        try:
            #For each Line in file
            for line in file_lines:
                words = line.split(",")
                #For each word
                for word in words:
                    # Add all words from the line to known_subdomains_new
                    self.add_new_subdomains(word)
        except:
            print('Error in add_all_top_10000_subdomains_from_file')


    def guess(self, output_len=None, domain=None):
        try:
            if output_len is None:
                output_len = self._output_len
            if domain is None:
                domain = self._domain

            
            #Golden Subdomains - 1 
            #First of all add MOST common subdomains AND MOST common PREFIXS on the initial knows subdomains
            if self._continue_to_add_flag:
                self.add_common_subdomains_and_prefixes()
                #if(len(self._known_subdomains) < self.num_of_output)
                
                with open(self._SUBDOMAINS_FILE) as file_text:
                    file_subdomains_lines = file_text.read().splitlines()

            #Golden Subdomains - 2
            #For each subdomain, add subdomains that are similar from subdomains text file
            if self._continue_to_add_flag:
                for subdomain in self._known_subdomains:
                    self.add_subdomain_from_same_lines_file(subdomain,file_subdomains_lines)

            #Golden Subdomains - 3
            #If known subdomains has a digit, add digits!
            if self._continue_to_add_flag:
                self.add_digits()



            #Silver Subdomains - 1
            #For each subdomain, add subdomains that are similar from subdomains text file with MOST common PREFIXS to them
            if self._continue_to_add_flag:           
                for subdomain in self._known_subdomains:
                    self.add_subdomain_from_same_lines_file_with_prefix(subdomain,file_subdomains_lines)

            #Silver Subdomains - 2
            #Add all subdomains from subdomains text file
            if self._continue_to_add_flag:           
                self.add_all_subdomains_from_file(file_subdomains_lines)

            #Silver Subdomains - 3
            #Add all subdomains from subdomains text file with MOST common PREFIXS to them
            if self._continue_to_add_flag:  
                self.add_all_subdomains_from_file_with_prefix(file_subdomains_lines)
            
            #Iron Subdomains - 1
            #Add variations of subdomains with digits-> Add digits to Golden Subdomains - 1
            if self._continue_to_add_flag:
                self.add_common_subdomains_and_prefixes_with_digits()

            #Iron Subdomains - 2
            #Add all subdomains from top_10000_subdomains text file
            if self._continue_to_add_flag:
                with open(self._TOP_10000_SUBDOMAINS_FILE) as file_text_top:
                    file_top_10000_subdomains_lines = file_text_top.read().splitlines()

                self.add_all_top_10000_subdomains_from_file(file_top_10000_subdomains_lines)

            #Iron Subdomains - 3
            #Add all subdomains from top_10000_subdomains text file with MOST common PREFIXS to them
            if self._continue_to_add_flag:
                self.add_all_subdomains_from_file_with_prefix(file_top_10000_subdomains_lines)

            return self.known_subdomains_new[:output_len]
        except:
                return self.known_subdomains_new
